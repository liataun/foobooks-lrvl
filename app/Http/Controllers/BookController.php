<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use App\Book;

class BookController extends Controller
{
    /**
     * GET /books
     */
    public function index()
    {
        # Get all the books from our library
        $books = Book::orderBy('title')->get();
        # Query on the existing collection to get our recently added books
        $newBooks = $books->sortByDesc('created_at')->take(3);

        return view('books.index')->with([
            'books' => $books,
            'newBooks' => $newBooks,
        ]);
    }

    /**
     * GET /books/{title}
     */
    public function show($id)
    {
        $book = Book::find($id);
        if (!$book) {
            return redirect('/books')->with(['alert' => 'Book not found']);
        }

        return view('books.show')->with([
            'book' => $book
        ]);
    }

    /**
     * GET /books/create
     * Display the form to add a new book
     */
    public function create(Request $request)
    {
        return view('books.create');
    }

    /**
     * GET /books/create
     * Display the form to add a new book
     */
    public function delete($id)
    {
        $book = Book::find($id)->delete();

        return redirect('books')->with([
            'alert' => 'Book deleted.'
        ]);
    }

    /**
     * GET /books/edit
     * Display the form to add a new book
     */
    public function edit($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return redirect('/books')->with([
                'alert' => 'Book not found.'
            ]);
        }

        return view('books.edit')->with([
            'book' => $book
        ]);
    }

    /*
    * PUT /books/{id}
    */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'author' => 'required',
            'published_year' => 'required|digits:4|numeric',
            'cover_url' => 'required|url',
            'purchase_url' => 'required|url',
        ]);

        $book = Book::find($id);
        $book->title = $request->input('title');
        $book->author = $request->input('author');
        $book->published_year = $request->input('published_year');
        $book->cover_url = $request->input('cover_url');
        $book->purchase_url = $request->input('purchase_url');
        $book->save();

        return redirect('/books/' . $id . '/edit')->with([
            'alert' => 'Your changes were saved.'
        ]);
    }

    /**
     * POST /books
     * Process the form for adding a new book
     */
    public function store(Request $request)
    {
        # Validate the request data
        $request->validate([
            'title' => 'required',
            'author' => 'required',
            'published_year' => 'required|digits:4',
            'cover_url' => 'required|url',
            'purchase_url' => 'required|url'
        ]);

        # Note: If validation fails, it will redirect the visitor back to the form page
        # and none of the code that follows will execute.

        $book = new Book();
        $book->title = $request->input('title');
        $book->author = $request->input('author');
        $book->published_year = $request->input('published_year');
        $book->cover_url = $request->input('cover_url');
        $book->purchase_url = $request->input('purchase_url');
        $book->save();

        return redirect('/books/create')->with([
            'alert' => 'Your book was added.'
        ]);
    }

    /**
     * GET
     * /books/search
     * Show the form to search for a book
     */
    public function search(Request $request)
    {
        return view('books.search')->with([
            'searchTerm' => $request->session()->get('searchTerm', ''),
            'caseSensitive' => $request->session()->get('caseSensitive', false),
            'searchResults' => $request->session()->get('searchResults', []),
        ]);
    }

    /**
     * GET
     * /books/search-process
     * Process the form to search for a book
     */
    public function searchProcess(Request $request)
    {
//        # ======== Temporary code to explore $request ==========
//
//        # See all the properties and methods available in the $request object
//        dump($request);
//
//        # See just the form data from the $request object
//        dump($request->all());
//
//        # See just the form data for a specific input, in this case a text input
//        dump($request->input('searchTerm'));
//
//        # See what the form data looks like for a checkbox
//        dump($request->input('caseSensitive'));
//
//        # Form data can also be accessed via dynamic properties
//        dump($request->searchTerm);
//
//        # Boolean to see if the request contains data for a particular field
//        dump($request->has('searchTerm')); # Should be true
//        dump($request->has('publishedYear')); # There's no publishedYear input, so this should be false
//
//        # You can get more information about a request than just the data of the form, for example...
//        dump($request->path()); # "books/search-process"
//        dump($request->is('books/search-process')); # True
//        dump($request->is('search')); # False
//        dump($request->fullUrl()); # e.g. http://foobooks.loc/books/search-process?searchTerm=abc
//        dump($request->method()); # GET
//        dump($request->isMethod('post')); # False
//
//        # ======== End exploration of $request ==========
        # Start with an empty array of search results; books that
        # match our search query will get added to this array
        $searchResults = [];

        # Store the searchTerm in a variable for easy access
        # The second parameter (null) is what the variable
        # will be set to *if* searchTerm is not in the request.
        $searchTerm = $request->input('searchTerm', null);

        # Only try and search *if* there's a searchTerm
        if ($searchTerm) {
                if ($request->has('caseSensitive')) {
                    $searchResults = Book::where('title','=',$searchTerm)->get();
                } else {
                    $searchResults = Book::where('title','=',$searchTerm)->get();
                }
        }

        # Redirect back to the search page w/ the searchTerm *and* searchResults (if any) stored in the session
        # Ref: https://laravel.com/docs/redirects#redirecting-with-flashed-session-data
        return redirect('/books/search')->with([
            'searchTerm' => $searchTerm,
            'caseSensitive' => $request->has('caseSensitive'),
            'searchResults' => $searchResults
        ]);
    }
}
