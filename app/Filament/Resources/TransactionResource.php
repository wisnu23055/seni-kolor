<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Transaction;
use App\Models\PaymentProof;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Support\Facades\Storage;

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
                    Transaction::STATUS_PENDING => 'Pending',
                    Transaction::STATUS_PROCESSING => 'Processing',
                    Transaction::STATUS_COMPLETED => 'Completed',
                    Transaction::STATUS_CANCELLED => 'Cancelled',
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
                    ->summarize(
                        Sum::make()
                            ->money('IDR')
                            ->label('Total Penjualan')
                    ),
                    
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => Transaction::STATUS_PENDING,
                        'info' => Transaction::STATUS_PROCESSING,
                        'success' => Transaction::STATUS_COMPLETED,
                        'danger' => Transaction::STATUS_CANCELLED,
                    ]),

                //  Kolom Status Bukti Pembayaran
                Tables\Columns\BadgeColumn::make('payment_proof_status')
                    ->label('Bukti Bayar')
                    ->getStateUsing(function (Transaction $record) {
                        return $record->paymentProof ? 'uploaded' : 'not_uploaded';
                    })
                    ->colors([
                        'success' => 'uploaded',
                        'danger' => 'not_uploaded',
                    ])
                    ->formatStateUsing(function (string $state) {
                        return $state === 'uploaded' ? 'Ada' : 'Belum';
                    }),
                    
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
                        Transaction::STATUS_PENDING => 'Pending',
                        Transaction::STATUS_PROCESSING => 'Processing',
                        Transaction::STATUS_COMPLETED => 'Completed',
                        Transaction::STATUS_CANCELLED => 'Cancelled',
                    ]),
                //  FILTER Bukti Pembayaran
                Tables\Filters\SelectFilter::make('payment_proof')
                    ->label('Bukti Pembayaran')
                    ->options([
                        'uploaded' => 'Sudah Upload',
                        'not_uploaded' => 'Belum Upload',
                    ])
                    ->query(function ($query, array $data) {
                        if ($data['value'] === 'uploaded') {
                            $query->has('paymentProof');
                        } elseif ($data['value'] === 'not_uploaded') {
                            $query->doesntHave('paymentProof');
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Detail Pesanan')
                    ->icon('heroicon-o-eye')
                    ->color('success')
                    ->modalHeading(fn (Transaction $record) => "Detail Pesanan #" . $record->id)
                    ->modalContent(function (Transaction $record) {
                        $record->load('transactionItems.product', 'user', 'umkm', 'paymentProof');
                        
                        // CSS untuk dukungan mode gelap + bukti pembayaran
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
                            
                            .payment-proof-img {
                                max-width: 300px;
                                height: auto;
                                border: 1px solid #ddd;
                                border-radius: 8px;
                                margin-top: 0.5rem;
                                cursor: pointer;
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
                            
                            .status-uploaded {
                                color: #16a34a;
                                font-weight: 500;
                            }
                            
                            .status-not-uploaded {
                                color: #dc2626;
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

                        //  SECTION  Bukti Pembayaran
                        $itemsHtml .= '<div class="detail-section">';
                        $itemsHtml .= '<div class="detail-title">Bukti Pembayaran</div>';
                        if ($record->paymentProof) {
                            $itemsHtml .= '<div><span class="detail-label">Status:</span> <span class="status-uploaded">Sudah Upload</span></div>';
                            $itemsHtml .= '<div><span class="detail-label">Tanggal Upload:</span> ' . $record->paymentProof->created_at->format('d M Y H:i') . '</div>';
                            if ($record->paymentProof->notes) {
                                $itemsHtml .= '<div><span class="detail-label">Catatan:</span> ' . $record->paymentProof->notes . '</div>';
                            }
                            if ($record->paymentProof->image) {
                                $imagePath = asset('storage/' . $record->paymentProof->image);
                                $itemsHtml .= '<div><span class="detail-label">Foto:</span><br>';
                                $itemsHtml .= '<img src="' . $imagePath . '" alt="Bukti Pembayaran" class="payment-proof-img" onclick="window.open(\'' . $imagePath . '\', \'_blank\')"></div>';
                            }
                        } else {
                            $itemsHtml .= '<div><span class="detail-label">Status:</span> <span class="status-not-uploaded">Belum Upload</span></div>';
                        }
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
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalWidth('5xl'), // Lebih lebar untuk menampung gambar

                //Upload Bukti Pembayaran
                Tables\Actions\Action::make('upload_payment_proof')
                    ->label('Edite Bukti')
                    ->icon('heroicon-o-camera')
                    ->color('warning')
                    ->visible(fn (Transaction $record) => !$record->paymentProof) // Hanya tampil jika belum ada bukti
                    ->form([
                        Forms\Components\FileUpload::make('image')
                            ->label('Foto Bukti Pembayaran')
                            ->image()
                            ->directory('payment-proofs')
                            ->required()
                            ->maxSize(5120) // 5MB
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg']),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan (Opsional)')
                            ->placeholder('Catatan tambahan tentang pembayaran...')
                            ->rows(3),
                    ])
                    ->action(function (Transaction $record, array $data) {
                        PaymentProof::create([
                            'transaction_id' => $record->id,
                            'image' => $data['image'],
                            'notes' => $data['notes'] ?? null,
                        ]);
                        
                        // Opsional: Update status transaksi menjadi processing
                        if ($record->status === Transaction::STATUS_PENDING) {
                            $record->update(['status' => Transaction::STATUS_PROCESSING]);
                        }
                    })
                    ->successNotificationTitle('Bukti pembayaran berhasil diupload'),

                //  Lihat Bukti Pembayaran
                Tables\Actions\Action::make('view_payment_proof')
                    ->label('Lihat Bukti')
                    ->icon('heroicon-o-photo')
                    ->color('info')
                    ->visible(fn (Transaction $record) => $record->paymentProof) // Hanya tampil jika ada bukti
                    ->modalHeading('Bukti Pembayaran')
                    ->modalContent(function (Transaction $record) {
                        $proof = $record->paymentProof;
                        $imagePath = asset('storage/' . $proof->image);
                        
                        $content = '<div style="text-align: center;">';
                        $content .= '<img src="' . $imagePath . '" alt="Bukti Pembayaran" style="max-width: 100%; height: auto; border-radius: 8px;">';
                        if ($proof->notes) {
                            $content .= '<div style="margin-top: 1rem; padding: 1rem; background-color: #f3f4f6; border-radius: 8px;">';
                            $content .= '<strong>Catatan:</strong><br>' . nl2br($proof->notes);
                            $content .= '</div>';
                        }
                        $content .= '</div>';
                        
                        return new HtmlString($content);
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalWidth('3xl'),

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
