<?php

namespace App\Filament\Resources\ProductResource\Pages;

use Filament\Actions;
use App\Models\Product;
use Filament\Forms\Form;
use App\Models\Attribute;
use Illuminate\Support\Str;
use App\Models\AttributeValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ProductResource;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class ProductVariant extends EditRecord
{
    protected static string $resource = ProductResource::class;
    protected static ?string $title = 'Product Variants';
    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    public function form(Form $form): Form
    {
        // $attributeOptions = Attribute::all()->map(function ($attribute) {
        //         return [
        //             $attribute->name => $attribute->name,
        //         ];
        //     })->reduce(function ($carry, $item) {
        //         return array_merge($carry, $item);
        //     }, []);
        return $form
            ->schema([
                Repeater::make('attributes')
                    ->columns([
                        'sm' => 3,
                        'xl' => 6,
                        '2xl' => 8,
                    ])
                    ->columnSpanFull() 
                    ->schema([
                        Select::make('attribute_id')
                            ->label('Attribute')                          
                            ->options(Attribute::all()->pluck('name', 'id'))
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (empty($state)) {
                                    $set('attribute_value_ids', null);
                                    return;
                                }

                                $this->record->attributes()->sync(collect($this->data['attributes'])->pluck('attribute_id')->unique());

                                $set('attribute_value_ids', null);
                            })
                            ->columnSpan([
                                'sm' => 1,
                                'xl' => 2,
                                '2xl' => 3,
                            ])
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Attribute Name')
                                    ->required()
                                    ->maxLength(255)
                                    ->reactive()
                            ])
                            ->createOptionUsing(function(array $data):int {
                                return Attribute::create([
                                    'name' => $data['name'],
                                ])->getKey();
                                
                            })
                            ->disableOptionWhen(function ($get) {
                                return $get('attributes')?->pluck('attribute_id')->contains($get('attribute_id'));
                            }),
                        Select::make('attribute_value_ids')
                            ->label('Attribute Values')
                            ->options(function ($get) {
                                $attributeId = $get('attribute_id');
                                return AttributeValue::where('attribute_id', $attributeId)
                                    ->pluck('value', 'id');
                            })
                            ->searchable()
                            ->multiple()
                            ->disabled(fn(callable $get) => !$get('attribute_id'))
                            ->createOptionForm([
                                TextInput::make('value')
                                    ->label('Attribute Value')
                                    ->required()
                                    ->unique(AttributeValue::class, 'value', ignoreRecord: true)
                                    ->maxLength(255)
                                    ->reactive()
                            ])
                            ->createOptionUsing(function(array $data, callable $get) {
                                return AttributeValue::create([
                                    'attribute_id' => $get('attribute_id'),
                                    'value' => $data['value'],
                                ])->getKey();
                            })
                            ->columnSpan([
                                'sm' => 2,
                                'xl' => 4,
                                '2xl' => 5,
                            ]),
                    ]),
                
                Section::make('Product Variant')
                ->columns(2)
                ->schema([
                    Select::make('action')
                        ->label('Select an Action')
                        ->options([
                            'generate' => 'Generate Variants from Attributes',
                            'delete' => 'Delete All Variants',
                        ])->suffixAction(
                            Action::make('go')
                                ->icon('heroicon-o-arrow-right')
                                ->action(function (callable $get, callable $set) {
                                    if ($get('action') === 'generate') {
                                        $this->generateVariants($get, $set);
                                    } elseif ($get('action') === 'delete') {
                                        // TODO: Implement delete logic
                                        $this->data = [
                                            'variants' => [],
                                        ];
                                    }
                                })
                        )
                        ->dehydrated(true),
                    Repeater::make('variants')
                        ->schema([
                            ...$this->getProductAttributeComponents(),
                            TextInput::make('sku')
                                ->label('SKU')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('price')
                                ->label('Price')
                                ->required()
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(1000000)
                                ->default($this->record->price)
                                ->placeholder('0.00'),
                            TextInput::make('stock')
                                ->label('Stock')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(1000000)
                                ->placeholder('0'),
                        ])->columns(2)
                        ->columnSpan(2)
                ]),
            ]); 
    }

    private function getProductAttributeComponents()
    {
        $comps = [];
        foreach ($this->record->attributes as $index => $attribute) {
            $comps[] = Select::make("attributes_$attribute->id")
                ->options(AttributeValue::where('attribute_id', $attribute->id)
                    ->pluck('value', 'id'))
                ->label("$attribute->name")
                ->columnSpan(1)
                ->required();
        }
        return $comps;
    }

    private function generateVariants($get, $set)
    {

        foreach($this->data['attributes'] as $attribute) {
            if (empty($attribute['attribute_id'])) {
                return;
            }
        }

        foreach ($this->data['attributes'] as $attribute) {
            $attributes[$attribute['attribute_id']] = $attribute['attribute_value_ids'] ?? [];
        }

        $keys = array_keys($attributes);
        $values = array_values($attributes);

        // Generate all combinations using recursive function
        $combinations = $this->cartesianProduct($values);
        // dd($combinations);
        // Map each combination to attribute names
        $variants = [];

        foreach ($combinations as $combo) {
            $variantAttributes = [];

            foreach ($combo as $index => $value) {
                $variantAttributes["attributes_" . $keys[$index]] = $value;
            }
            $variants[] = [
                ...$variantAttributes,
                'sku' => '',
                'price' => $this->record->price,
            ];
        }

        $this->data['variants'] = $variants;
        
    }

    // Recursive Cartesian product generator
    private function cartesianProduct(array $arrays): array
    {
        $result = [[]];

        foreach ($arrays as $property) {
            $tmp = [];

            foreach ($result as $product) {
                foreach ($property as $item) {
                    $tmp[] = array_merge($product, [$item]);
                }
            }
            $result = $tmp;
        }
        return $result;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {

        // get all attribute values for the product variants
        $productVariantAttributeValues = $this->record->variants->map(function ($variant) {
            return $variant->attributeValues->pluck('id')->toArray();
        })->flatten()->unique()->toArray();
        // fill the form data for attributes that the prodct has and with the attribute values that are associated with the product variants
        $data['attributes'] = $this->record->attributes->map(function ($attribute) use ($productVariantAttributeValues) {
            return ['attribute_id' => $attribute->id, 'attribute_value_ids' => $attribute->attributeValues->whereIn('id', $productVariantAttributeValues)->pluck('id')->toArray()];
        })->toArray();

        // fill the form data for variants that the product has
        $data['variants'] = $this->record->variants->map(function ($variant) {
            $attributes = [];
            foreach ($variant->attributeValues as $value) {
                $attributes["attributes_" . $value->attribute->id] = $value->id;
            }
            return array_merge($attributes, [
                'sku' => $variant->sku,
                'price' => $variant->price,
                'stock' => $variant->stock,
            ]);
        })->toArray();
        // dd('Mutate Form Data Before Fill Hook Triggered', $data);
        return $data;
    }

    
    protected function handleRecordUpdate(Model $record, array $data): Model
    {

        $record->variants()->delete();
        foreach ($this->data['variants'] as $key => $variant) {
            $attributeValues = [];
            foreach ($variant as $k => $v) {
                if (Str::startsWith($k, 'attributes_')) {
                    $attributeValues[] = $v;
                    unset($this->data['variants'][$key][$k]);
                }
            }
            $variantModel = $record->variants()->create($this->data['variants'][$key]);
            $variantModel->attributeValues()->sync($attributeValues);
        }
        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('variants', [
            'record' => $this->record,
        ]);
    }

}
