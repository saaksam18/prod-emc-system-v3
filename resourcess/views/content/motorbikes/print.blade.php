<style>
    .container {
        width: 100%;
        display: flex;
        /* Aligns the tables horizontally side-by-side */
        flex-direction: row;
        /* Prevents wrapping to a new line if space is limited */
        flex-wrap: nowrap;
        /* Optional: add space between tables */
        gap: 10px;
        }
    .container-1 {
    width: 50%;
    display: flex;
    /* Aligns the tables horizontally side-by-side */
    flex-direction: column;
    /* Optional: add space between tables */
    gap: 10px;
    }
    .table1, .table2 {
        /* Makes each table flexible and takes up equal space */
        flex: 2;
        padding: 10px;
        }
    table {
        border: 1px solid #DDD;
        border-collapse: collapse;
        }
    th{
            color: #083763;
        }
    th, td {
        padding: 8px;
        text-align: left;
        border: 1px solid #DDD;
        }

        tr:hover {background-color: #D6EEEE;}
</style>
    <div class="container">
        <table class="table1">
            <thead>
                <tr>
                    <th>
                        No.
                    </th>
                    <th>
                        Status
                    </th>
                    <th>
                        Model
                    </th>
                    <th>
                        Type
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($motorbikes as $motorbike)
                <tr>
                    <td>{{ $motorbike->motorno }}</td>
                    @if ($motorbike->motorStatus == 1)
                    <td>
                        <span class="badge bg-primary text-white">
                            In Stock
                        </span>
                    </td>
                    @elseif ($motorbike->motorStatus == 2)
                    <td>
                        <span class="badge bg-success text-white">
                            On Rent
                        </span>
                    </td>
                    @elseif ($motorbike->motorStatus == 3)
                    <td>
                        <span class="badge bg-danger text-white">
                            Sold
                        </span>
                    </td>
                    @elseif ($motorbike->motorStatus == 4)
                    <td>
                        <span class="badge bg-danger text-white">
                            Lost / Stolen
                        </span>
                    </td>
                    @elseif ($motorbike->motorStatus == 5)
                    <td>
                        <span class="badge bg-primary text-white">
                            Temporary Return
                        </span>
                    </td>
                    @endif
                    <td>{{ $motorbike->motorModel }}</td>
                    @if ($motorbike->motorType == 1)
                        <td>Big AT</td>
                    @elseif ($motorbike->motorType == 2)
                        <td>Auto</td>
                    @elseif ($motorbike->motorType == 3)
                        <td>50cc AT</td>
                        @elseif ($motorbike->motorType == 4)
                        <td>Manual</td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="container-1">
            <table class="table2">
                <thead>
                    <tr>
                        <td>Motorbike Type</td>
                        <td>Total In Stock</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Big Auto</td>
                        <td class="text-center">
                            {{ $bigATis }}  @if ($tempBigATis != NULL)
                                (TM Return: {{ $tempBigATis }})
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Auto</td>
                        <td class="text-center">
                            {{ $atis }}  @if ($tempATis != NULL)
                            (TM Return: {{ $tempATis }})
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>50cc Auto</td>
                        <td class="text-center">
                            {{ $ccATis }}  @if ($tempCCATis != NULL)
                            (TM Return: {{ $tempCCATis }})
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Manual</td>
                        <td class="text-center">
                            {{ $mtis }}  @if ($tempMTis != NULL)
                            (TM Return: {{ $tempMTis }})
                            @endif
                        </td>
                    </tr>
                    <tr class="bg-dark">
                        <td class="text-white text-center">Total</td>
                        <td class="text-white text-center">{{ $totalInstock }}</td>
                    </tr>
                </tbody>
            </table>
            <table class="table2">
                <thead>
                    <tr>
                        <td>Motorbike Model</td>
                        <td>Total In Stock (Include TM Return)</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($motorbikeCounts as $motorbikeCount)
                        <tr>
                            <td>{{ $motorbikeCount->motorModel }}</td>
                            <td>{{ $motorbikeCount->total_count }}</td>
                        </tr>
                    @endforeach
                        <tr style="font-weight: bold; color: #083763">
                            <td>Total In Stock</td>
                            <td>{{ $motorbikeTotalCounts }}</td>
                        </tr>
                </tbody>
            </table>
    </div>
    </div>