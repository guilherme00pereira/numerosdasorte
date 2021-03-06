<div class="d-flex flex-row my-2 flex-wrap justify-content-around">
    @foreach ($stats as $stat)
        <div class="d-flex flex-row p-4 bg-white rounded shadow-sm h-100 mt-3" style="width: 420px; height: 220px;">
            <div class="stats_image">
                <img src="{{ asset('images/' . $stat['image']) }}" width="80" height="80"/>
            </div>
            <div class="d-flex flex-column text-wrap stats_content">
                <h6>{{ $stat['pretitle'] }}</h6>
                <h2>{{ $stat['title'] }}</h2>
                <small>{{ $stat['subtitle'] }}</small>
            </div>

        </div>
    @endforeach
</div>
