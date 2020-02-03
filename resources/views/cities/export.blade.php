<table border="1">
  <thead>
    <tr>
      <th rowspan="2">No</th>
      <th rowspan="2">Origin</th>
      <th rowspan="2">Destination</th>

      @foreach ($services as $service)
        <th colspan="2">{{ $service }}</th>
      @endforeach
    </tr>
    <tr>
      @foreach ($services as $service)
        <th>Cost</th>
        <th>Est</th>
      @endforeach
    </tr>
  </thead>
  <tbody>
    @if (count($costs))
      @php $no = 1; @endphp
      @foreach ($costs as $id => $item)
        <tr>
          <td>{{ $no++ }}</td>
          <td>{{ $item['data']['origin']['type'] . ' ' . $item['data']['origin']['name'] }}</td>
          <td>{{ $item['data']['destination']['type'] . ' ' . $item['data']['destination']['name'] }}</td>


          @foreach ($services as $service)
            @if (isset($item['costs'][$service]))
              <td>{{ $item['costs'][$service]['cost'] }}</td>
              <td>{{ $item['costs'][$service]['estimation'] }}</td>
            @else
              <td></td>
              <td></td>
            @endif
          @endforeach
        </tr>
      @endforeach
    @endif
  </tbody>
</table>
