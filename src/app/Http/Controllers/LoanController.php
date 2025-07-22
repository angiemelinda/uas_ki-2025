<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Book;
use App\Models\Member;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::with(['book', 'member'])->get();
        return view('loans.index', compact('loans'));
    }

    public function create()
    {
        $books = Book::where('stock', '>', 0)->get();
        $members = Member::all();
        return view('loans.create', compact('books', 'members'));
    }

    public function store(Request $request): mixed
    {
        $request->validate([
            'book_id'   => 'required|exists:books,id',
            'member_id' => 'required|exists:members,id',
            'loan_date' => 'required|date',
            'return_date' => 'required|date|after:loan_date',
        ]);

        $book = Book::findOrFail($request->book_id);
        if ($book->stock < 1) {
            return back()->withErrors(['book_id' => 'Stok buku habis.']);
        }

        $loan = Loan::create($request->all());

        // Kurangi stok buku
        $book->decrement('stock');

        return redirect()->route('loans.index')->with('success', 'Peminjaman berhasil dicatat.');
    }

    public function returnBook(Loan $loan)
    {
        if (!$loan->return_date) {
            $loan->update(['return_date' => now()]);

            // Tambahkan kembali stok buku
            $loan->book->increment('stock');
        }

        return redirect()->route('loans.index')->with('success', 'Buku berhasil dikembalikan.');
    }

    public function destroy(Loan $loan)
    {
        if (!$loan->return_date) {
            $loan->book->increment('stock');
        }

        $loan->delete();

        return redirect()->route('loans.index')->with('success', 'Data peminjaman dihapus.');
    }
}
