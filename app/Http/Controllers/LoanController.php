<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\User;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    public function index()
    {
        $loans = DB::table('loans')
            ->leftJoin('users as librarian', 'loans.librarian_id', '=', 'librarian.id')
            ->leftJoin('users as member', 'loans.member_id', '=', 'member.id')
            ->leftJoin('books', 'loans.book_id', '=', 'books.id')
            ->select(
                'loans.id',
                'librarian.name as librarian_name',
                'member.name as member_name',
                'books.title as book_title',
                'loans.loan_at',
                'loans.returned_at',
                'loans.note'
            )
            ->get();

        return response()->json(['data' => $loans]);
    }

    public function users()
    {
        $users = User::orderBy('name')->get(['id', 'name', 'role']);
        return response()->json(['data' => $users]);
    }

    public function books()
    {
        $books = Book::orderBy('title')->get(['id', 'title']);
        return response()->json(['data' => $books]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'librarian_id' => 'required|exists:users,id',
            'member_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'loan_at' => 'required|date',
            'returned_at' => 'nullable|date|after_or_equal:loan_at',
            'note' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], Response::HTTP_BAD_REQUEST);
        }

        $loan = Loan::create([
            'librarian_id' => $request->librarian_id,
            'member_id' => $request->member_id,
            'book_id' => $request->book_id,
            'loan_at' => $request->loan_at,
            'returned_at' => $request->returned_at,
            'note' => $request->note,
        ]);

        $loan->load(['librarian', 'member', 'book']);

        return response()->json([
            'data' => [
                'id' => $loan->id,
                'librarian_name' => $loan->librarian->name ?? '-',
                'member_name' => $loan->member->name ?? '-',
                'book_title' => $loan->book->title ?? '-',
                'loan_at' => $loan->loan_at,
                'returned_at' => $loan->returned_at,
                'note' => $loan->note,
            ]
        ], Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $loan = Loan::findOrFail($id);
        return response()->json(['data' => $loan]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'librarian_id' => 'required|exists:users,id',
            'member_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'loan_at' => 'required|date',
            'returned_at' => 'nullable|date|after_or_equal:loan_at',
            'note' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], Response::HTTP_BAD_REQUEST);
        }

        $loan = Loan::findOrFail($id);
        $loan->update([
            'librarian_id' => $request->librarian_id,
            'member_id' => $request->member_id,
            'book_id' => $request->book_id,
            'loan_at' => $request->loan_at,
            'returned_at' => $request->returned_at,
            'note' => $request->note,
        ]);

        $loan->load(['librarian', 'member', 'book']);

        return response()->json([
            'data' => [
                'id' => $loan->id,
                'librarian_name' => $loan->librarian->name ?? '-',
                'member_name' => $loan->member->name ?? '-',
                'book_title' => $loan->book->title ?? '-',
                'loan_at' => $loan->loan_at,
                'returned_at' => $loan->returned_at,
                'note' => $loan->note,
            ]
        ]);
    }

    public function destroy($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function view()
    {
        return view('loans');
    }
}