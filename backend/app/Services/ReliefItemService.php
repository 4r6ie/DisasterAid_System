<?php

namespace App\Services;

use App\Models\ReliefItem;
use App\Models\InventoryTransaction;
use App\Repositories\ReliefItemRepository;
use App\Repositories\InventoryTransactionRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ReliefItemService
{
    public function __construct(
        protected ReliefItemRepository $itemRepository,
        protected InventoryTransactionRepository $transactionRepository
    ) {}

    public function getAllItems(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->itemRepository->getFiltered($filters, $perPage);
    }

    public function getItemById(int $id): ?ReliefItem
    {
        return $this->itemRepository->find($id);
    }

    public function getItemByIdOrFail(int $id): ReliefItem
    {
        return $this->itemRepository->findOrFail($id);
    }

    public function createItem(array $data): ReliefItem
    {
        return $this->itemRepository->create($data);
    }

    public function updateItem(ReliefItem $item, array $data): ReliefItem
    {
        return $this->itemRepository->update($item, $data);
    }

    public function deleteItem(ReliefItem $item): bool
    {
        return $this->itemRepository->delete($item);
    }

    public function stockIn(ReliefItem $item, array $data): InventoryTransaction
    {
        $item->increment('quantity', $data['quantity']);

        return $this->transactionRepository->create([
            'relief_item_id' => $item->id,
            'type' => 'in',
            'quantity' => $data['quantity'],
            'reference_type' => $data['reference_type'] ?? 'purchase',
            'reference_id' => $data['reference_id'] ?? null,
            'reference_number' => $data['reference_number'] ?? null,
            'unit_cost' => $data['unit_cost'] ?? null,
            'total_cost' => $data['unit_cost'] ? $data['unit_cost'] * $data['quantity'] : null,
            'processed_by' => auth()->id(),
            'notes' => $data['notes'] ?? null,
            'transaction_date' => $data['transaction_date'] ?? now(),
        ]);
    }

    public function stockOut(ReliefItem $item, array $data): InventoryTransaction
    {
        if ($item->quantity < $data['quantity']) {
            throw new \Exception('Insufficient stock');
        }

        $item->decrement('quantity', $data['quantity']);

        return $this->transactionRepository->create([
            'relief_item_id' => $item->id,
            'type' => 'out',
            'quantity' => $data['quantity'],
            'reference_type' => $data['reference_type'] ?? 'usage',
            'reference_id' => $data['reference_id'] ?? null,
            'reference_number' => $data['reference_number'] ?? null,
            'unit_cost' => $data['unit_cost'] ?? null,
            'total_cost' => $data['unit_cost'] ? $data['unit_cost'] * $data['quantity'] : null,
            'processed_by' => auth()->id(),
            'notes' => $data['notes'] ?? null,
            'transaction_date' => $data['transaction_date'] ?? now(),
        ]);
    }

    public function distribute(ReliefItem $item, array $data): InventoryTransaction
    {
        if ($item->quantity < $data['quantity']) {
            throw new \Exception('Insufficient stock for distribution');
        }

        $item->decrement('quantity', $data['quantity']);

        return $this->transactionRepository->create([
            'relief_item_id' => $item->id,
            'type' => 'distribute',
            'quantity' => $data['quantity'],
            'reference_type' => 'distribution',
            'reference_id' => $data['reference_id'] ?? null,
            'reference_number' => $data['reference_number'] ?? null,
            'evacuation_center_id' => $data['evacuation_center_id'] ?? null,
            'disaster_incident_id' => $data['disaster_incident_id'] ?? null,
            'unit_cost' => $data['unit_cost'] ?? null,
            'total_cost' => $data['unit_cost'] ? $data['unit_cost'] * $data['quantity'] : null,
            'processed_by' => auth()->id(),
            'notes' => $data['notes'] ?? null,
            'transaction_date' => $data['transaction_date'] ?? now(),
        ]);
    }

    public function getTransactions(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->transactionRepository->getFiltered($filters, $perPage);
    }

    public function getItemTransactions(int $itemId, int $limit = 50): Collection
    {
        return $this->transactionRepository->getByReliefItem($itemId, $limit);
    }

    public function getStatistics(): array
    {
        return $this->itemRepository->getStatistics();
    }

    public function getLowStock(int $limit = 50): Collection
    {
        return $this->itemRepository->getLowStock($limit);
    }

    public function getExpiringSoon(int $days = 30, int $limit = 50): Collection
    {
        return $this->itemRepository->getExpiringSoon($days, $limit);
    }
}