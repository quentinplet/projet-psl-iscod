<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class DataQueryService
{
    /**
     * Applies search filter to the query.
     */
    public function filter(Builder $query, string $search, $category_id, $supplier_id, $status): Builder
    {
        if (!empty($search)) {
            $query->search($search);
        }

        // Les produits peuvent être filtrés par catégorie et fournisseur
        if (!empty($category_id)) {
            $query->byCategory($category_id);
        }

        if (!empty($supplier_id)) {
            $query->bySupplier($supplier_id);
        }

        // Les commandes peuvent être filtrées par statut
        if (!empty($status)) {
            $query->filterByStatus($status);
        }


        return $query;
    }

    /**
     * Applies sorting to the query.
     */
    public function sort(Builder $query, string $sortField, string $sortDirection): Builder
    {

        if (method_exists($query->getModel(), 'scopeSortByField')) {
            return $query->sortByField($sortField, $sortDirection);
        } else {
            return $query->orderBy($sortField, $sortDirection);
        }
    }

    /**
     * Applies pagination to the query.
     */
    public function paginate(Builder $query, int $perPage): LengthAwarePaginator
    {
        return $query->paginate($perPage);
    }

    /**
     * Control sortField and sortDirection parameters.
     */
    public function validateSortParams(string $sortField, string $sortDirection): array
    {
        $validSortFields = ['id', 'name', 'status', 'price', 'created_at', 'updated_at', 'total_price', 'quantity'];
        $validSortDirections = ['asc', 'desc'];
        if (!in_array($sortField, $validSortFields) || empty($sortField)) {
            $sortField = 'updated_at'; // default sort field
        }
        if (!in_array($sortDirection, $validSortDirections) || empty($sortDirection)) {
            $sortDirection = 'desc'; // default sort direction
        }
        return [$sortField, $sortDirection];
    }

    /**
     * Gets a filtered, sorted, and paginated list of items.
     */
    public function getFilteredList(Builder $query, array $params): LengthAwarePaginator
    {
        $search = $params['search'] ?? '';
        $perPage = $params['per_page'] ?? 10;

        $category_id = $params['category_id'] ?? null;
        $supplier_id = $params['supplier_id'] ?? null;

        $sortField = $params['sort_field'] ?? 'updated_at';
        $sortDirection = $params['sort_direction'] ?? 'desc';

        [$sortField, $sortDirection] = $this->validateSortParams($sortField, $sortDirection);


        $status = $params['status'] ?? null;

        $query = $this->filter($query, $search, $category_id, $supplier_id, $status);
        $query = $this->sort($query, $sortField, $sortDirection);

        return $this->paginate($query, $perPage);
    }
}
