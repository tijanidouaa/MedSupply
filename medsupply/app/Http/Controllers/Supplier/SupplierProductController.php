<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SupplierProductController extends Controller
{
    public function index()
    {
        $supplier = Supplier::where('user_id', Auth::id())->first();
        $products = $supplier
            ? Product::where('supplier_id', $supplier->id)->latest()->paginate(15)
            : collect();
        return view('supplier.products.index', compact('products'));
    }

    public function create()
    {
        return view('supplier.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'price'     => 'required|numeric|min:0',
            'category'  => 'required|string',
            'stock'     => 'nullable|integer|min:0',
            'min_stock' => 'nullable|integer|min:0',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $supplier = Supplier::where('user_id', Auth::id())->first();

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'name'        => $request->name,
            'price'       => $request->price,
            'category'    => $request->category,
            'stock'       => $request->stock ?? 0,
            'min_stock'   => $request->min_stock ?? 10,
            'image'       => $imagePath,
            'supplier_id' => $supplier?->id,
        ]);

        return redirect()->route('supplier.products.index')
                         ->with('success', 'Produit ajouté avec succès.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Supprimer l'image du storage si elle existe
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('supplier.products.index')
                         ->with('success', 'Produit supprimé.');
    }

    // -------------------------------------------------------
    // IMPORT CSV
    // -------------------------------------------------------

    public function importForm()
    {
        return view('supplier.products.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $supplier = Supplier::where('user_id', Auth::id())->first();
        if (!$supplier) {
            return back()->with('error', 'Fournisseur introuvable.');
        }

        $file     = $request->file('csv_file');
        $handle   = fopen($file->getRealPath(), 'r');
        $header   = null;
        $inserted = 0;
        $skipped  = 0;

        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            if (!$header) {
                $header = array_map('trim', $row);
                continue;
            }

            if (empty(array_filter($row))) continue;

            $data = array_combine($header, $row);

            if (empty($data['name']) || empty($data['price'])) {
                $skipped++;
                continue;
            }

            $imagePath = null;
            if (!empty($data['image'])) {
                $expectedPath = 'products/' . trim($data['image']);
                if (file_exists(storage_path('app/public/' . $expectedPath))) {
                    $imagePath = $expectedPath;
                }
            }

            Product::create([
                'name'        => trim($data['name']),
                'category'    => trim($data['category']   ?? 'autres'),
                'price'       => floatval($data['price']   ?? 0),
                'stock'       => intval($data['stock']     ?? 0),
                'min_stock'   => intval($data['min_stock'] ?? 10),
                'image'       => $imagePath,
                'supplier_id' => $supplier->id,
            ]);

            $inserted++;
        }

        fclose($handle);

        return redirect()->route('supplier.products.index')
                         ->with('success', "$inserted produit(s) importé(s) avec succès." .
                                           ($skipped ? " $skipped ligne(s) ignorée(s)." : ''));
    }

    public function catalog()
    {
    $supplier = Supplier::where('user_id', Auth::id())->first();
    $products = $supplier
        ? Product::where('supplier_id', $supplier->id)->latest()->get()
        : collect();
    return view('supplier.catalog.index', compact('products'));
    }
}