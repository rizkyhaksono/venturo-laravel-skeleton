<table>
    <thead>
        <tr>
            <th style="width: 170px; vertical-align: middle;" rowspan="2" id="menu">Menu</th>
            <th colspan="{{count($dates)}}" class="text-center" id="periode">Periode</th>
            <th style="width: 70px; vertical-align: middle;" rowspan="2" id="total">Total</th>
        </tr>
        <tr>
            @foreach($dates as $date)
            <th scope="col">
                {{date('d', strtotime($date))}}
            </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($data as $category)
        <tr class="category">
            <td colspan="{{count($dates) + 1}}">
                {{$category['category_name']}}
            </td>
            <td class="nominal">
                Rp {{number_format($category['category_total'])}}
            </td>
        </tr>
        @foreach($category['products'] as $product)
        <tr>
            <td>
                {{$product['product_name']}}
            </td>
            @foreach($product['transactions'] as $sale)
            <td class="nominal">
                Rp {{number_format($sale['total_sales'])}}
            </td>
            @endforeach
            <td class="nominal">
                Rp {{number_format($product['transactions_total'])}}
            </td>
        </tr>
        @endforeach
        <tr class="category">
            <td>Grand Total</td>
            @foreach($total_per_date as $total)
            <td class="nominal">
                Rp {{number_format($total)}}
            </td>
            @endforeach
            <td class="nominal">
                Rp {{number_format($grand_total)}}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>