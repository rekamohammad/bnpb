<style>
.linkterkait{
	list-style:none;
	margin:0;
	padding:0;
}

.linkterkait li{
	padding:5px 0;
}
.linkterkait li a{
	display:block;
	padding-bottom:5px;
	border-bottom:1px solid #ccc;
	font-weight:800;
}

</style>
<ul class="linkterkait">
	<li><a href="{{ url('/nasionals') }}">Nasional</a></li>
	<li><a href="{{ url('/internasionals') }}">Internasional</a></li>
	<li><a href="{{ url('/bpbd-provinsi') }}">BPBD Provinsi</a></li>
	<li><a href="{{ url('/bpbd-kabupaten') }}">BPBD Kabupaten/Kota</a></li>
</ul>