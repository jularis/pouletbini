<?php

namespace App\Exports;

use App\Constants\Status;
use App\Models\LivraisonInfo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class ManagerDeliveryQueueExport implements WithMultipleSheets
{
    protected $rows;

    public function __construct($rows)
    {
        $this->rows = $rows;
    }

    public function sheets(): array
    {
        $rows = $this->detailedRows();

        if ($rows->isEmpty()) {
            return [
                new ManagerDeliveryQueueStaffSheet('Export', collect()),
            ];
        }

        return $rows
            ->groupBy(function ($row) {
                return $row['sender_staff'] ?: 'Sans Staff';
            })
            ->map(function ($staffRows, $staffName) {
                return new ManagerDeliveryQueueStaffSheet($staffName, $staffRows->values());
            })
            ->values()
            ->all();
    }

    protected function detailedRows()
    {
        $codes = collect($this->rows)->pluck('code')->filter()->unique()->values();

        if ($codes->isEmpty()) {
            return collect();
        }

        $livraisons = LivraisonInfo::with([
            'senderMagasin',
            'senderStaff',
            'receiverMagasin',
            'receiverClient',
            'paymentInfo',
            'products.produit.categorie',
        ])->whereIn('code', $codes)->get()->keyBy('code');

        return $codes->flatMap(function ($code) use ($livraisons) {
            $livraison = $livraisons->get($code);

            if (!$livraison) {
                return [];
            }

            if ($livraison->products->isEmpty()) {
                return [$this->row($livraison)];
            }

            return $livraison->products->map(function ($detail) use ($livraison) {
                return $this->row($livraison, $detail);
            });
        });
    }

    public function headings(): array
    {
        return [
            'Code',
            'Magasin Expediteur',
            'Staff Expediteur',
            'Destinataire',
            'Client / Telephone',
            'Adresse Client',
            'Categorie',
            'Produit',
            'Quantite',
            'Prix Unitaire',
            'Total Ligne',
            'Montant Commande',
            'Date estimee',
            'Status Paiement',
            'Status Livraison',
        ];
    }

    protected function row($livraison, $detail = null): array
    {
        $produit = $detail ? $detail->produit : null;

        return [
            'code' => $livraison->code,
            'sender_magasin' => $livraison->senderMagasin->name ?? '',
            'sender_staff' => $livraison->senderStaff->fullname ?? '',
            'destinataire' => $livraison->receiverMagasin->name ?? $livraison->receiver_name,
            'client_phone' => trim(($livraison->receiverClient->name ?? '') . ' / ' . ($livraison->receiver_phone ?? ''), ' /'),
            'receiver_address' => $livraison->receiverClient->address ?? $livraison->receiver_address,
            'categorie' => $produit->categorie->name ?? '',
            'produit' => $produit->name ?? '',
            'quantite' => $detail->qty ?? '',
            'prix_unitaire' => $detail->type_price ?? '',
            'total_ligne' => $detail->fee ?? '',
            'montant_commande' => $livraison->paymentInfo->final_amount ?? 0,
            'date_estimee' => $livraison->estimate_date,
            'status_paiement' => $this->paymentStatusLabel($livraison->paymentInfo->status ?? null),
            'status_livraison' => $this->livraisonStatusLabel($livraison->status ?? null),
        ];
    }

    protected function paymentStatusLabel($status): string
    {
        return match ((int) $status) {
            Status::PAID => 'Paye',
            Status::PARTIAL => 'Partiel',
            Status::UNPAID => 'Impaye',
            default => '',
        };
    }

    protected function livraisonStatusLabel($status): string
    {
        return match ((int) $status) {
            Status::COURIER_QUEUE => 'En attente',
            Status::COURIER_DELIVERYQUEUE => 'En attente de Reception',
            Status::COURIER_DELIVERED => 'Livre',
            default => '',
        };
    }
}

class ManagerDeliveryQueueStaffSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $title;
    protected $rows;

    public function __construct($title, $rows)
    {
        $this->title = $this->cleanTitle($title);
        $this->rows = $rows;
    }

    public function collection()
    {
        return collect($this->rows);
    }

    public function headings(): array
    {
        return [
            'Code',
            'Magasin Expediteur',
            'Staff Expediteur',
            'Destinataire',
            'Client / Telephone',
            'Adresse Client',
            'Categorie',
            'Produit',
            'Quantite',
            'Prix Unitaire',
            'Total Ligne',
            'Montant Commande',
            'Date estimee',
            'Status Paiement',
            'Status Livraison',
        ];
    }

    public function title(): string
    {
        return $this->title;
    }

    protected function cleanTitle($title): string
    {
        $title = preg_replace('/[\\\\\\/\\?\\*\\[\\]\\:]/', ' ', (string) $title);
        $title = trim(preg_replace('/\\s+/', ' ', $title));

        if ($title === '') {
            $title = 'Sans Staff';
        }

        return substr($title, 0, 31);
    }
}
