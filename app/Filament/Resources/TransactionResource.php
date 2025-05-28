<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\Summarizers\Sum;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Transactions';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->relationship('user', 'name')
                ->searchable()
                ->required(),

            Forms\Components\Select::make('umkm_id')
                ->relationship('umkm', 'name')
                ->searchable()
                ->required(),

            Forms\Components\TextInput::make('total_amount')
                ->numeric()
                ->required()
                ->prefix('Rp'),

            Forms\Components\Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'processing' => 'Processing',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled',
                ])
                ->required(),

            Forms\Components\TextInput::make('shipping_name')->required(),
            Forms\Components\TextInput::make('shipping_phone')->required(),
            Forms\Components\Textarea::make('shipping_address')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID Pesanan')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable()
                    // ✅ BENAR - Gunakan Sum summarizer
                    ->summarize(
                        Sum::make()
                            ->money('IDR')
                            ->label('Total Penjualan')
                    ),
                    
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'primary' => 'pending',
                        'warning' => 'processing', 
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ]),
                    
                Tables\Columns\TextColumn::make('shipping_name')
                    ->label('Penerima'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i'),
            ])
            ->filters([
                // Filter periode
                Tables\Filters\Filter::make('periode')
                    ->form([
                        Forms\Components\DatePicker::make('start')->label('Tanggal Mulai'),
                        Forms\Components\DatePicker::make('end')->label('Tanggal Selesai'),
                    ])
                    ->query(function ($query, array $data) {
                        if ($data['start']) {
                            $query->whereDate('created_at', '>=', $data['start']);
                        }
                        if ($data['end']) {
                            $query->whereDate('created_at', '<=', $data['end']);
                        }
                    }),
                // Filter status
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Detail Pesanan')
                    ->icon('heroicon-o-eye')
                    ->color('success')
                    ->modalHeading(fn (Transaction $record) => "Detail Pesanan #" . $record->id)
                    ->modalContent(function (Transaction $record) {
                        $record->load('transactionItems.product', 'user', 'umkm');
                        
                        // CSS untuk dukungan mode gelap
                        $darkModeStyle = '
                        <style>
                            .detail-section {
                                padding: 1rem;
                                border-radius: 0.375rem;
                                margin-bottom: 1rem;
                                background-color: rgb(249 250 251);
                            }
                            
                            .dark .detail-section {
                                background-color: rgb(55 65 81);
                                color: rgb(229 231 235);
                            }
                            
                            .detail-title {
                                font-weight: 500;
                                font-size: 1.125rem;
                                margin-bottom: 0.5rem;
                            }
                            
                            .detail-grid {
                                display: grid;
                                grid-template-columns: repeat(2, minmax(0, 1fr));
                                gap: 1rem;
                            }
                            
                            .detail-label {
                                font-weight: 500;
                            }
                            
                            .detail-table {
                                min-width: 100%;
                                border-collapse: collapse;
                            }
                            
                            .dark .detail-table th {
                                background-color: rgb(75 85 99);
                                color: rgb(243 244 246);
                            }
                            
                            .dark .detail-table td {
                                border-color: rgb(75 85 99);
                            }
                            
                            .detail-table th, .detail-table td {
                                padding: 0.5rem 1rem;
                                text-align: left;
                            }
                            
                            .detail-table th {
                                background-color: rgb(243 244 246);
                            }
                            
                            .detail-table tbody {
                                background-color: white;
                            }
                            
                            .dark .detail-table tbody {
                                background-color: rgb(55 65 81);
                            }
                            
                            .detail-table tfoot {
                                font-weight: 500;
                            }
                        </style>
                        ';
                        
                        $itemsHtml = $darkModeStyle . '<div class="space-y-4">';
                        
                        // Informasi transaksi
                        $itemsHtml .= '<div class="detail-section">';
                        $itemsHtml .= '<div class="detail-title">Informasi Pesanan</div>';
                        $itemsHtml .= '<div class="detail-grid">';
                        $itemsHtml .= '<div><span class="detail-label">Customer:</span> ' . $record->user->name . '</div>';
                        $itemsHtml .= '<div><span class="detail-label">UMKM:</span> ' . ($record->umkm->name ?? 'N/A') . '</div>';
                        $itemsHtml .= '<div><span class="detail-label">Tanggal:</span> ' . $record->created_at->format('d M Y H:i') . '</div>';
                        $itemsHtml .= '<div><span class="detail-label">Status:</span> ' . ucfirst($record->status) . '</div>';
                        $itemsHtml .= '</div>';
                        $itemsHtml .= '</div>';
                        
                        // Detail pengiriman
                        $itemsHtml .= '<div class="detail-section">';
                        $itemsHtml .= '<div class="detail-title">Detail Pengiriman</div>';
                        $itemsHtml .= '<div><span class="detail-label">Nama Penerima:</span> ' . $record->shipping_name . '</div>';
                        $itemsHtml .= '<div><span class="detail-label">Telepon:</span> ' . $record->shipping_phone . '</div>';
                        $itemsHtml .= '<div><span class="detail-label">Alamat:</span> ' . nl2br($record->shipping_address) . '</div>';
                        $itemsHtml .= '</div>';
                        
                        // List produk yang dipesan
                        $itemsHtml .= '<div class="detail-section">';
                        $itemsHtml .= '<div class="detail-title">Produk yang Dipesan</div>';
                        
                        if ($record->transactionItems->count() > 0) {
                            $itemsHtml .= '<table class="detail-table">';
                            $itemsHtml .= '<thead>';
                            $itemsHtml .= '<tr>';
                            $itemsHtml .= '<th>Produk</th>';
                            $itemsHtml .= '<th>Jumlah</th>';
                            $itemsHtml .= '<th>Harga</th>';
                            $itemsHtml .= '<th>Subtotal</th>';
                            $itemsHtml .= '</tr>';
                            $itemsHtml .= '</thead>';
                            $itemsHtml .= '<tbody>';
                            
                            foreach ($record->transactionItems as $item) {
                                $itemsHtml .= '<tr>';
                                $itemsHtml .= '<td>' . ($item->product->name ?? 'Produk tidak tersedia') . '</td>';
                                $itemsHtml .= '<td>' . $item->quantity . '</td>';
                                $itemsHtml .= '<td>Rp ' . number_format($item->price, 0, ',', '.') . '</td>';
                                $itemsHtml .= '<td>Rp ' . number_format($item->price * $item->quantity, 0, ',', '.') . '</td>';
                                $itemsHtml .= '</tr>';
                            }
                            
                            $itemsHtml .= '</tbody>';
                            $itemsHtml .= '<tfoot>';
                            $itemsHtml .= '<tr>';
                            $itemsHtml .= '<td colspan="3" style="text-align: right;">Total</td>';
                            $itemsHtml .= '<td style="font-weight: bold;">Rp ' . number_format($record->total_amount, 0, ',', '.') . '</td>';
                            $itemsHtml .= '</tr>';
                            $itemsHtml .= '</tfoot>';
                            $itemsHtml .= '</table>';
                        } else {
                            $itemsHtml .= '<div style="text-align: center; padding: 1rem;">Tidak ada produk yang dipesan</div>';
                        }
                        
                        $itemsHtml .= '</div>';
                        $itemsHtml .= '</div>';
                        
                        return new HtmlString($itemsHtml);
                    })
                    ->modalSubmitAction(false) // Menghilangkan tombol submit default
                    ->modalCancelActionLabel('Tutup') // Mengubah label tombol cancel menjadi "Tutup"
                    ->modalWidth('4xl'),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
