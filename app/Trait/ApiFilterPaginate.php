<?php
namespace App\Trait;

use Illuminate\Http\Request;

trait ApiFilterPaginate
{
    /**
     * Apply filters, eager loading, pagination and resource transformation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  array  $allowedColumns
     * @param  array  $withRelations
     * @param  string $resourceClass
     * @param  int    $defaultPerPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function filterPaginateResource(
        Request $request,
        $query,
        array $allowedColumns,
        array $withRelations,
        string $resourceClass,
        int $defaultPerPage = 10
    ) {
        // eager loading
        if (! empty($withRelations)) {
            $query->with($withRelations);
        }

        $column = $request->input('search');
        $value  = $request->input('value');

        // if ($column && $value && in_array($column, $allowedColumns)) {
        //     $query->where($column, 'LIKE', '%' . $value . '%');
        // }
        $column = $request->input('search');
        $value  = $request->input('value');

       if ($column && $value) {
    // special case: if user passes "brand", search in relation brand.name
    if ($column === 'brand') {
        $query->whereHas('brand', function ($q) use ($value) {
            $q->where('name', 'LIKE', '%' . $value . '%');
        });
    }
    // handle relation search (e.g. brand.name)
    elseif (str_contains($column, '.') && in_array($column, $allowedColumns)) {
        [$relation, $relationColumn] = explode('.', $column, 2);
        $query->whereHas($relation, function ($q) use ($relationColumn, $value) {
            $q->where($relationColumn, 'LIKE', '%' . $value . '%');
        });
    }
    // handle direct column search (only if exists in allowedColumns)
    elseif (in_array($column, $allowedColumns)) {
        $query->where($column, 'LIKE', '%' . $value . '%');
    }
}
        // pagination
        $perPage   = $request->input('pageNum', $defaultPerPage);
        $paginated = $query->paginate($perPage);

        // transform using Resource
        $paginated->getCollection()->transform(function ($item) use ($resourceClass) {
            return new $resourceClass($item);
        });

        return $paginated;
    }

    public function filterPaginateResourceForEmployee(
        Request $request,
        $query,
        array $withRelations,
        string $resourceClass,
        int $defaultPerPage = 10,
        $employeeId
    ) {
        // eager loading
        if (! empty($withRelations)) {
            $query->with($withRelations);
        }

        // ربط البيانات بالموظف فقط (buyer OR installer)
        $query->where(function ($q) use ($employeeId) {
            $q->where('admin_buy_id', $employeeId)
                ->orWhere('admin_install_id', $employeeId);
        });

        // filter بالـ projectName أو غيره
        $name = $request->query('name');

        $query->where('projectName', 'LIKE', '%' . $name . '%');

        // pagination
        $perPage   = $request->input('pageNum', $defaultPerPage);
        $paginated = $query->paginate($perPage);

        // transform using Resource
        $paginated->getCollection()->transform(function ($item) use ($resourceClass) {
            return new $resourceClass($item);
        });

        return $paginated;
    }

    public function completedOrdersForEmployee(
        Request $request,
        $query,
        array $withRelations,
        string $resourceClass,
        int $defaultPerPage = 10,
        $employeeId
    ) {
        // eager loading
        if (! empty($withRelations)) {
            $query->with($withRelations);
        }

        // ربط البيانات بالموظف (buyer OR installer)
        $query->where(function ($q) use ($employeeId) {
            $q->where('admin_buy_id', $employeeId)
                ->orWhere('admin_install_id', $employeeId);
        });

        // نجيب بس اللى تم شراؤه أو تركيبه
        $query->where(function ($q) {
            $q->whereNotNull('admin_buy_id')
                ->orWhereNotNull('admin_install_id');
        });

        // فلترة بالاسم لو موجود
        if ($request->filled('name')) {
            $name = $request->query('name');
            $query->where('projectName', 'LIKE', '%' . $name . '%');
        }

        // pagination
        $perPage   = $request->input('pageNum', $defaultPerPage);
        $paginated = $query->paginate($perPage);

        // transform using Resource
        $paginated->getCollection()->transform(function ($item) use ($resourceClass) {
            return new $resourceClass($item);
        });

        return $paginated;
    }

}
