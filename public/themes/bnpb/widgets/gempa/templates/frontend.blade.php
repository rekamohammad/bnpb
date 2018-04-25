<div class="aside-box">
    <div class="aside-box-header">
        <h4>{{ __($config['name']) }}</h4>
    </div>

    <div class="aside-box-content">
        @php
            $url = "http://data.bmkg.go.id/gempaterkini.xml";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $xmlresponse = curl_exec($ch);
            $bmkg = simplexml_load_string($xmlresponse);
            $bmkg = json_decode( json_encode($bmkg) , 1);

            foreach ($bmkg as $data) {
                $coordinates = explode(",", $data[0]['point']['coordinates']);
                $tanggal    = $data[0]['Tanggal'];
                $jam        = $data[0]['Jam'];
                $wilayah    = $data[0]['Wilayah'];
                $kedalaman  = $data[0]['Kedalaman'];
                $latitude   = $coordinates[1];
                $longitude  = $coordinates[0];
                $mag        = $data[0]['Magnitude'];
            }

            $date = explode("-", $data[0]['Tanggal']);
            
            switch ($date[1]) {
                case "Jan":
                    $newDate = "20".$date[2]."01".$date[0];
                    break;
                case "Feb":
                    $newDate = "20".$date[2]."02".$date[0];
                    break;
                case "Mar":
                    $newDate = "20".$date[2]."03".$date[0];
                    break;
                case "Apr":
                    $newDate = "20".$date[2]."04".$date[0];
                    break;
                case "May":
                    $newDate = "20".$date[2]."05".$date[0];
                    break;
                case "Jun":
                    $newDate = "20".$date[2]."06".$date[0];
                    break;
                case "Jul":
                    $newDate = "20".$date[2]."07".$date[0];
                    break;
                case "Aug":
                    $newDate = "20".$date[2]."08".$date[0];
                    break;
                case "Sep":
                    $newDate = "20".$date[2]."09".$date[0];
                    break;
                case "Oct":
                    $newDate = "20".$date[2]."10".$date[0];
                    break;
                case "Nov":
                    $newDate = "20".$date[2]."11".$date[0];
                    break;
                case "Dec":
                    $newDate = "20".$date[2]."12".$date[0];
                    break;
                default:
                    $newDate = "Month not detected";
            }
            $newTime =  filter_var($jam, FILTER_SANITIZE_NUMBER_INT);
		    
            $filenameGempa = $newDate.''.$newTime;
        @endphp
        
        @php 
            $image = "http://dataweb.bmkg.go.id/INATEWS/shakemaprasa/".$filenameGempa.".jpg";
            if (file_exists($image)) {
                $image = "http://dataweb.bmkg.go.id/INATEWS/shakemaprasa/".$filenameGempa.".jpg";
            } else {
                $image = "http://data.bmkg.go.id/eqmap.gif";
            }
        @endphp

        <div class="img-maps">
            <a href="{{ $image }}" target="_blank">
                <img src="{{ $image }}" class="img-responsive" style="width:100%;">
            </a>
            <div>
                <p class="font13 fontarial">
                    <span class="fa fa-calendar" aria-hidden="true"></span> {{ __('tabs.waktu_gempa') }} : {{ $tanggal }}
            </div>
            <div>
                <p class="font13 fontarial">
                    <span class="fa fa-location-arrow" aria-hidden="true"></span> {{ __('tabs.magnitudo') }} : {{ $mag }}
            </div>
            <div>
                <p class="font13 fontarial">
                    <span class="fa fa-map-o" aria-hidden="true"></span> {{ __('tabs.kedalaman') }} : {{ $kedalaman }}
            </div>
            <div>
                <p class="font15 fontarial">
                    <span class="fa fa-flag-o" aria-hidden="true"></span> {{ __('tabs.lokasi') }} : {{ $wilayah }}
            </div>
            <p style="text-align: right;">
                <strong>
                    <a href="http://www.bmkg.go.id/gempabumi/gempabumi-terkini.bmkg" target=_blank>{{ __('tabs.selengkapnya') }}>></a>
                </strong>
            </p>
        </div>
    </div>
</div>
