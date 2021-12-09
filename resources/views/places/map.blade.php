@extends('layouts.app')

@section('content')
<div class="container">
    <div class="form-group">
        <input type="text" name="" id="textsearch" placeholder="Cari cepat lokasi disini....." class="form-control">
    </div>
    <div id="mapid">
        <div class="card">
            <div class="card-body"></div>
            <x:notify-messages />
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Lokasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <div class="card card-body">
                            <strong>Nama Lokasi</strong>
                            <p class="text-muted" id="nama"></p>
                            <hr>

                            <strong>Deskripsi</strong>
                            <p class="text-muted" id="deskripsi"></p>
                            <hr>
                            
                            <strong>Alamat Lengkap</strong>
                            <p class="text-muted" id="alamat"></p>
                            <hr>
                        </div>
                    </div>
                    <div class="col-6">
                        <div id="image"></div>
                        {{-- <img class="img-responsive rounded" src="{{ asset('file/1639055483.png') }}"> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<!-- Leaflet CSS -->
      <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
      integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
      crossorigin=""/>
      <link rel="stylesheet" href="https://labs.easyblog.it/maps/leaflet-search/src/leaflet-search.css">
    <style>
      #mapid { min-height: 500px; }
      .card{
          border: none;
      }
    </style>
@endsection

@push('scripts')
 <!-- Leaflet JavaScript -->
      <!-- Make sure you put this AFTER Leaflet's CSS -->
      <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
          integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
          crossorigin="">
      </script>
      <script src="https://labs.easyblog.it/maps/leaflet-search/src/leaflet-search.js"></script>
<script>
    var map = L.map('mapid').setView([{{ config('leafletsetup.map_center_latitude') }},
    {{ config('leafletsetup.map_center_longitude') }}],
    {{ config('leafletsetup.zoom_level') }});

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    axios.get('{{ route('api.places.index') }}')
    .then(function (response) {
        //console.log(response.data);
        L.geoJSON(response.data,{
            pointToLayer: function(geoJsonPoint,latlng) {
                return L.marker(latlng);
            }
        })
        .bindPopup(function(layer) {
            //return layer.feature.properties.map_popup_content;
            return ('<div class="card bg-white card-body">'+
                        '<div class="my-2">'+
                            '<strong>Nama</strong> :<br>'+layer.feature.properties.place_name+
                        '</div>'+
                        '<div class="my-2">'+
                            '<strong>Deskripsi</strong>:<br>'+layer.feature.properties.description+
                        '</div>'+
                        '<div class="my-2">'+
                            '<strong>Alamat Lengkap</strong>:<br>'+layer.feature.properties.address+
                        '</div>'+
                        '<button type="button" onclick="myModal('+layer.feature.properties.id+')" class="btn btn-primary btn-sm">'+
                            'modal'+
                        '</button>'+
                    '</div>');
        }).addTo(map);
        console.log(response.data);
    })
    .catch(function (error) {
        console.log(error);
    });

    //SIMPLE SEARCH LOCATION
    var data = [
        <?php
        foreach ($places as $key => $value) {
        ?>
            {"loc":[<?= $value->latitude ?>,<?= $value->longitude ?>], "title": '<?= $value->place_name ?>'},
        <?php } ?>
    ];

	var markersLayer = new L.LayerGroup();	//layer contain searched elements

	map.addLayer(markersLayer);
    console.log(data);
	var controlSearch = new L.Control.Search({
		position:'topleft',
		layer: markersLayer,
		initial: false,
		zoom: 17,
		markerLocation: true
	})
	map.addControl( controlSearch );
	////////////populate map with markers from sample data
	for(i in data) {
		var title = data[i].title,	//value searched
			loc = data[i].loc,		//position found
			marker = new L.Marker(new L.latLng(loc), {title: title} );//se property searched
		marker.bindPopup('title: '+ title );
		markersLayer.addLayer(marker);
	}
    // SIMPLE SEARCH LOCATION
    $('#textsearch').on('keyup', function(e) {

    controlSearch.searchText( e.target.value );
    });
    
    function myModal(id) {
        
        $("#myModal").modal('show');
        $.ajax({
            url:"{{ route('api.places.data', '') }}"+"/"+id,  
            method:"POST",  
            data:{id : id},                              
            success: function(data) {
                console.log(data);
                $("#nama").html(data.place_name);
                $("#deskripsi").html(data.description);
                $("#alamat").html(data.address);
                var image = data.image;
	            $("#image").html('<img class="img-responsive rounded" src="{{ URL::asset("file") }}/'+image+'">');
            }
        });
    }

</script>


@endpush


