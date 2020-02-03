@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">Cities</div>

        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>No</th>
                  <th></th>
                  <th>Province</th>
                  <th>City</th>
                </tr>
              </thead>
              <tbody>
                @if (count($cities))
                  @php $no = 1; @endphp
                  @foreach ($cities as $item)
                    <tr>
                      <td>{{ $no++ }}</td>
                      <td>
                        <a class="btn btn-info btn-sm" href="{{ route('cities.show', [$item->id]) }}">
                          Get Cost
                        </a>

                        <a class="btn btn-success btn-sm" href="{{ route('cities.export', [$item->id]) }}">
                          Export
                        </a>
                      </td>
                      <td>{{ $item->province }}</td>
                      <td>
                        {{ $item->type }}
                        {{ $item->name }}
                      </td>
                    </tr>
                  @endforeach
                @else
                  <tr>
                    <td>No Data</td>
                  </tr>
                @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
