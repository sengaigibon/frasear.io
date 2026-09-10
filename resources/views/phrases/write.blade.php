<x-layouts.frasear title="Escribir frase — frasear.io">
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('write.store') }}" method="POST">
        @csrf
        <div>
            <label for="body">Phrase</label><br>
            <textarea id="body" name="body" rows="4" cols="60">{{ old('body') }}</textarea>
        </div>
        <div>
            <label for="author">Author (optional)</label><br>
            <input id="author" name="author" value="{{ old('author') }}">
        </div>
        <div>
            <button type="submit">Save</button>
        </div>
    </form>

</x-layouts.frasear>