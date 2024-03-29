<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Storehouse;
use App\Models\Product;
use App\Models\Category;
use App\Models\Product_storehouse;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StorehousesManagementController extends Controller
{
    public function showBackOfficeAll()
    {
        return view('backoffice.management.showProducts');
    }

    public function showProductsAjax($storehouseSelectedId = 0, $categorySelectedId = 0, $searchProductId = 0, $offset = 0, $historic = 'false')
    {
        $categories = Category::all();
        $storehouses = Storehouse::all();
        $products = Product::all();

        $fillWheres = '';

        $storeAnd = " AND storehouses.id = " . $storehouseSelectedId;
        $catAnd = " AND categories.id = " . $categorySelectedId;
        $searchAnd = " AND products.id = " . $searchProductId;

        if ($storehouseSelectedId == 0 && $categorySelectedId != 0 && $searchProductId == 0) {
            $fillWheres = $catAnd;
        } elseif ($storehouseSelectedId != 0 && $categorySelectedId == 0 && $searchProductId == 0) {
            $fillWheres = $storeAnd;
        } elseif ($categorySelectedId != 0 && $storehouseSelectedId != 0 && $searchProductId == 0) {
            $fillWheres = $storeAnd . ' ' . $catAnd;
        } elseif ($storehouseSelectedId == 0 && $categorySelectedId != 0 && $searchProductId != 0) {
            $fillWheres = $catAnd  . ' ' .  $searchAnd;
        } elseif ($storehouseSelectedId != 0 && $categorySelectedId == 0 && $searchProductId != 0) {
            $fillWheres = $storeAnd  . ' ' .  $searchAnd;
        } elseif ($categorySelectedId != 0 && $storehouseSelectedId != 0 && $searchProductId != 0) {
            $fillWheres = $storeAnd  . ' ' .  $catAnd . ' ' . $searchAnd;
        } elseif ($categorySelectedId == 0 && $storehouseSelectedId == 0 && $searchProductId != 0) {
            $fillWheres = $searchAnd;
        }

        if ($historic == 'true') {

            $filtered = Db::select("SELECT storehouses.name AS sname, items.id, storehouses.prefix AS sprefix, storehouses.description AS sdescription, products.prefix AS pprefix, products.name AS pname, products.price AS pprice, categories.name AS cname, items.updated_at, items.quantity, items.action, items.stock FROM items
            INNER JOIN product_storehouses ON product_storehouses.id = items.item_has_product_storehouses
            INNER JOIN products ON products.id = product_storehouses.product_storehouse_has_products
            INNER JOIN storehouses ON storehouses.id = product_storehouses.product_storehouse_has_storehouses
            INNER JOIN categories ON categories.id = products.product_has_category
            $fillWheres GROUP BY storehouses.name, items.id, storehouses.prefix, storehouses.description, products.prefix, products.name, products.price, categories.name, items.updated_at, items.quantity, items.action, items.stock ORDER BY storehouses.name, items.updated_at;");

            [$pagination, $totalPrd] = $this->paginator(count($filtered));

            $filtered = Db::select("SELECT storehouses.name AS sname, items.id, storehouses.prefix AS sprefix, storehouses.description AS sdescription, products.prefix AS pprefix, products.name AS pname, products.price AS pprice, categories.name AS cname, items.updated_at, items.quantity, items.action, items.stock FROM items
            INNER JOIN product_storehouses ON product_storehouses.id = items.item_has_product_storehouses
            INNER JOIN products ON products.id = product_storehouses.product_storehouse_has_products
            INNER JOIN storehouses ON storehouses.id = product_storehouses.product_storehouse_has_storehouses
            INNER JOIN categories ON categories.id = products.product_has_category
            $fillWheres GROUP BY storehouses.name, storehouses.prefix, items.id, storehouses.description, products.prefix, products.name, products.price, categories.name, items.updated_at, items.quantity, items.action, items.stock 
            ORDER BY storehouses.name, items.updated_at ASC LIMIT 10 OFFSET $offset");
        } elseif ($historic == 'false') {

            $filtered = Db::select("SELECT storehouses.name AS sname, t.id, storehouses.prefix AS sprefix, storehouses.description AS sdescription, products.prefix AS pprefix, products.name AS pname, products.price AS pprice, categories.name AS cname, t.updated_at, t.quantity, t.action, t.stock FROM (SELECT *, ROW_NUMBER() OVER (PARTITION BY item_has_product_storehouses ORDER BY updated_at DESC) AS rowNumber FROM items) t
            INNER JOIN product_storehouses ON product_storehouses.id = t.item_has_product_storehouses
            INNER JOIN products ON products.id = product_storehouses.product_storehouse_has_products
            INNER JOIN storehouses ON storehouses.id = product_storehouses.product_storehouse_has_storehouses
            INNER JOIN categories ON categories.id = products.product_has_category
            WHERE t.rowNumber = 1 
            $fillWheres");

            [$pagination, $totalPrd] = $this->paginator(count($filtered));
            $filtered = Db::select("SELECT storehouses.name AS sname, t.id, storehouses.prefix AS sprefix, storehouses.description AS sdescription, products.prefix AS pprefix, products.name AS pname, products.price AS pprice, categories.name AS cname, t.updated_at, t.quantity, t.action, t.stock FROM (SELECT *, ROW_NUMBER() OVER (PARTITION BY item_has_product_storehouses ORDER BY updated_at DESC) AS rowNumber FROM items) t
            INNER JOIN product_storehouses ON product_storehouses.id = t.item_has_product_storehouses
            INNER JOIN products ON products.id = product_storehouses.product_storehouse_has_products
            INNER JOIN storehouses ON storehouses.id = product_storehouses.product_storehouse_has_storehouses
            INNER JOIN categories ON categories.id = products.product_has_category
            WHERE t.rowNumber = 1 
            $fillWheres GROUP BY storehouses.name, storehouses.prefix, t.id, storehouses.description, products.prefix, products.name, products.price, categories.name, t.updated_at, t.quantity, t.action, t.stock 
            ORDER BY storehouses.name, t.updated_at ASC LIMIT 10 OFFSET $offset");
        }

        return ['storehouses' => $storehouses, 'categories' => $categories, 'products' => $products, 'filtered' => $filtered, 'pagination' => $pagination, 'searchProductId' => $searchProductId, 'offset' => $offset, 'totalPrd' => $totalPrd];
    }

    function is_decimal($val)
    {
        return is_numeric($val) && floor($val) != $val;
    }

    function paginator($totalPrd)
    {
        $offsetGroups = $totalPrd / 10;

        if ($this->is_decimal($offsetGroups)) $offsetGroups = round($offsetGroups);
        else $offsetGroups = round($offsetGroups);

        $pagination = array();

        for ($i = 0; $i < $offsetGroups; $i++) {
            $pagination[] = (object)[
                'page' => $i,
                'offset' => $i * 10
            ];
        }

        return [$pagination, $totalPrd];
    }

    public function searchBackOfficeByProduct($inputSearch = '')
    {
        if ($inputSearch != '') {
            $productFiltered = Product::where('products.name', 'LIKE', '%' . $inputSearch . '%')
                ->get();
        } else {
            $productFiltered = null;
        }

        return $productFiltered;
    }

    public function backOfficeAddToStorehouse(Request $request)
    {
        $itemId = Product_storehouse::where('product_storehouse_has_storehouses', $request->storehouse)
            ->where('product_storehouse_has_products', $request->product)
            ->value('id');

        $latest = Db::table('items')
            ->where('item_has_product_storehouses', $itemId)
            ->orderBy('updated_at', 'desc')
            ->take('1')
            ->get();

        Item::create(['item_has_product_storehouses' => $itemId, 'action' => $request->action, 'pricepu' => $request->price, 'quantity' =>  $request->quantity, 'stock' => ($latest[0]->stock + $request->quantity)]);

        return true;
    }

    public function backOfficeRemoveFromStorehouse($itemId)
    {
        $product_storehouseId = Item::where('id', $itemId)->value('item_has_product_storehouses');

        $delete = Item::find($itemId);
        $delete->delete();

        $items = Item::select('id')->where('id', '>', $itemId)->where('item_has_product_storehouses', $product_storehouseId)->get();


        foreach ($items as $item) {
            Db::table('items')->where('id', $item->id)->decrement('stock', $delete->quantity);
        }

        return true;
    }
}
