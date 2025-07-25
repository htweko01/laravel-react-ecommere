<?php

namespace App\Filament\Resources\ProductResource\Pages;

use Filament\Actions;
use App\Models\Product;
use Filament\Forms\Form;
use Illuminate\Support\Str;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ProductResource;
use App\Models\AttributeValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends EditRecord
{
    protected static string $resource = ProductResource::class;
    protected static ?string $title = 'Product Variants';
    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    public function form(Form $form): Form
    {
                $components = [];


        foreach ($this->record->attributes as $attribute) {
            $components[] = Select::make($attribute->name)
                ->options(AttributeValue::where('attribute_id', $attribute->id)
                    ->pluck('value', 'id'))
                ->label("Product $attribute->name" . 's')
                ->multiple()
                ->required(false)
                ->dehydrated(true);
        }

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
                            ->options(\App\Models\Attribute::pluck('name', 'id'))
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('attribute_value_ids', null);
                            })
                            ->columnSpan([
                                'sm' => 1,
                                'xl' => 2,
                                '2xl' => 3,
                            ]),
                        Select::make('attribute_value_ids')
                            ->label('Attribute Values')
                            ->options(function ($get) {
                                $attributeId = $get('attribute_id');
                                return \App\Models\AttributeValue::where('attribute_id', $attributeId)
                                    ->pluck('value', 'id');
                            })
                            ->searchable()
                            ->multiple()
                            ->columnSpan([
                                'sm' => 2,
                                'xl' => 4,
                                '2xl' => 5,
                            ]),
                    ]),
                // Select::make('attributes')
                //     ->label('Attributes')
                //     ->relationship('attributes', 'name')
                //     ->columnSpan('2')
                //     ->multiple()
                //     ->preload()
                //     ->searchable()
                //     ->required()
                //     ->reactive()
                //     ->afterStateUpdated(function (string $operation, $state, callable $set) {
                //         $this->record->attributes()->sync($state);
                //     }),
                // ...$components,
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
                        // ->relationship('variants')
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
            $comps[] = Select::make("attributes_$attribute->name")
                ->options(AttributeValue::where('attribute_id', $attribute->id)
                    ->pluck('value', 'id'))
                ->label("$attribute->name")
                ->columnSpan(1)
                ->required();
        }
        return $comps;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $productVariantAttributeValues = $this->record->variants->map(function ($variant) {
            return $variant->attributeValues->pluck('id')->toArray();
        })->toArray();
        $data['attributes'] = $this->record->attributes->map(function ($attribute) use ($productVariantAttributeValues) {
            return ['attribute_id' => $attribute->id, 'attribute_value_ids' => $attribute->attriuteValues?->whereIn('id', $productVariantAttributeValues)->pluck('id')->toArray()];
        })->toArray();
        return $data;
    }

    
    // protected function beforeValidate(): void
    // {
    //     dd('Before Validate Hook Triggered', $this->data);
    // }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->attributes()->sync(collect($data['attributes'])->pluck('attribute_id')->unique());
        // $record->variants()->delete();
        // foreach ($this->data['variants'] as $key => $variant) {
        //     $attributeValues = [];
        //     foreach ($variant as $k => $v) {
        //         if (Str::startsWith($k, 'attributes_')) {
        //             $attributeValues[] = $v;
        //             unset($this->data['variants'][$key][$k]);
        //         }
        //     }
        //     $variantModel = $record->variants()->create($this->data['variants'][$key]);
        //     $variantModel->attributeValues()->sync($attributeValues);
        // }
        return $record;
    }

}
