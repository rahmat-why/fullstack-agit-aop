<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookController extends Controller
{
    public function view()
    {
        return view('books');
    }

    public function index()
    {
        $books = DB::table('books')
            ->whereNull('books.deleted_at')
            ->leftJoin('book_categories', 'books.id', '=', 'book_categories.book_id')
            ->leftJoin('categories', 'book_categories.category_id', '=', 'categories.id')
            ->select(
                'books.id',
                'books.title',
                'books.authors',
                'books.isbn',
                'books.description',
                'books.created_at',
                'books.updated_at',
                DB::raw('GROUP_CONCAT(categories.name) as category_names')
            )
            ->groupBy(
                'books.id',
                'books.title',
                'books.authors',
                'books.isbn',
                'books.description',
                'books.created_at',
                'books.updated_at'
            )
            ->get();

        return response()->json(['data' => $books]);
    }

    public function getDeleted()
    {
        $books = DB::table('books')
            ->whereNotNull('books.deleted_at')
            ->leftJoin('book_categories', 'books.id', '=', 'book_categories.book_id')
            ->leftJoin('categories', 'book_categories.category_id', '=', 'categories.id')
            ->select(
                'books.id',
                'books.title',
                'books.authors',
                'books.isbn',
                'books.description',
                'books.deleted_at',
                DB::raw('GROUP_CONCAT(categories.name) as category_names')
            )
            ->groupBy(
                'books.id',
                'books.title',
                'books.authors',
                'books.isbn',
                'books.description',
                'books.deleted_at'
            )
            ->get();

        return response()->json(['data' => $books]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'authors' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id',
        ]);

        $book = Book::create($request->only('title', 'authors', 'isbn', 'description'));

        if ($request->has('categories')) {
            $book->categories()->attach($request->categories);
        }

        return response()->json(['data' => $book], Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $book = Book::with('categories:id')->findOrFail($id);

        $categoryIds = $book->categories->pluck('id')->map(fn($id) => (string) $id)->toArray();

        return response()->json([
            'data' => [
                'id' => $book->id,
                'title' => $book->title,
                'authors' => $book->authors,
                'isbn' => $book->isbn,
                'description' => $book->description,
                'category_ids' => $categoryIds,
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'authors' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id',
        ]);

        $book->update($request->only('title', 'authors', 'isbn', 'description'));

        if ($request->has('categories')) {
            $book->categories()->sync($request->categories);
        } else {
            $book->categories()->detach();
        }

        return response()->json(['data' => $book]);
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->deleted_at = Carbon::now();
        $book->save();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function categories()
    {
        $categories = \App\Models\Category::select('id', 'name')->orderBy('name')->get();
        return response()->json(['data' => $categories]);
    }
}