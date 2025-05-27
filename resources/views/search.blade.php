<ul>
    @foreach ($results as $result)
        <li>
            <strong>{{ $result->CustomerName }}</strong>
            <p>{{ $result->motorno }}</p>
        </li>
    @endforeach
    </ul>