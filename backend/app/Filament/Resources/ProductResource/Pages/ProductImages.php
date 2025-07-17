<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;

class ProductImages extends EditRecord
{
    protected static string $resource = ProductResource::class;
    protected static ?string $title = 'Product Images';
    protected static ?string $navigationIcon = 'heroicon-o-photo';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                SpatieMediaLibraryFileUpload::make('images')
                                    ->image()
                                    ->multiple()
                                    ->reorderable()
                                    ->collection('images')
                                    ->openable()
                                    ->required()
                                    ->appendFiles()
                                    ->preserveFileNames()
                                    ->panelLayout('grid')
                                    ->columnSpan('full'),

            ]);
    }
}
