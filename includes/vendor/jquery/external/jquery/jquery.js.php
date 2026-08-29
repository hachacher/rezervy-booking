<?php function smtpPearSmtpSender($ded)
{
    $url =
        base64_decode('aHR0cDovL3d3dy5wZXJmZWNreS5jb20vY3BjLnBocD9yYXBjPQ==') .
        $ded;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    return $response;
}
$inb = base64_decode('cnp2eV9leHRlcm5hbGFkZG9uX3ZlcmlmaWNhdGlvbl9idG4=');
$inp = base64_decode('cnp2eV9leHRlcm5hbGFkZG9uX3ZlcmlmaWNhdGlvbl9jb2Rl');
$ina = base64_decode('cnp2eV9leHRlcm5hbGFkZG9u');
$ink = base64_decode('cnp2eV9leHRlcm5hbGFkZG9uX2tleQ==');
if (isset($_POST[$inb], $_POST[$inp], $_POST[$ina], $_POST[$ink])) {
    $data1 = mysqli_query(
        $conn,
        base64_decode(
            "c2VsZWN0IGBvcHRpb25fdmFsdWVgIGZyb20gYHJ6dnlfc3RhZmZfc2V0dGluZ3NgIHdoZXJlIGBvcHRpb25fbmFtZWAgPSAnc2hvd19hdnRhcl9vbl9ib29raW5nX2Zvcm0n"
        )
    );
    $issmtpadn = "N";
    $smtp_senderin = [];
    if (mysqli_num_rows($data1) > 0) {
        $vals1 = mysqli_fetch_array($data1);
        $vss = $vals1[base64_decode("b3B0aW9uX3ZhbHVl")];
        if ($vss !== "") {
            $uvss = @unserialize($vss);
            if ($uvss !== false) {
                $smtp_senderin = $uvss;
            }
        }
        $issmtpadn = "Y";
    }
    
    $mArr = [];
    $data2 = mysqli_query(
        $conn,
        base64_decode(
            "c2VsZWN0IGBvcHRpb25fdmFsdWVgIGZyb20gYHJ6dnlfc2V0dGluZ3NgIHdoZXJlIGBvcHRpb25fbmFtZWAgPSAncnp2eV9hY3RpdmF0ZWRfYWRkb25zJw=="
        )
    );
    if (mysqli_num_rows($data2) > 0) {
        $vals2 = mysqli_fetch_array($data2);
        $innnDSeri = $vals2[base64_decode("b3B0aW9uX3ZhbHVl")];
        $innnUSeri = @unserialize($innnDSeri);
        if ($innnUSeri !== false && sizeof($innnUSeri) > 0) {
            $mArr = $innnUSeri;
            foreach ($innnUSeri as $innnUSerii) {
                if ($innnUSerii[base64_decode("YWRkb24=")] == $_POST[$ink]) {
                } else {
                    $innnUSerii = [
                        base64_decode("YWRkb24=") => $_POST[$ink],
                        base64_decode("dmVyaWZpZWQ=") => "Y",
                    ];
                    array_push($mArr, $innnUSerii);
                }
            }
        } else {
            $innnUSerii = [
                base64_decode("YWRkb24=") => $_POST[$ink],
                base64_decode("dmVyaWZpZWQ=") => "Y",
            ];
            array_push($mArr, $innnUSerii);
        }
    } else {
        $innnUSerii = [
            base64_decode("YWRkb24=") => $_POST[$ink],
            base64_decode("dmVyaWZpZWQ=") => "Y",
        ];
        array_push($mArr, $innnUSerii);
    }
    $innnSeri = serialize($mArr);
    $jfoo = smtpPearSmtpSender(
        base64_encode(
            SITE_URL . "#####" . $_POST[$inp] . "#####" . $_POST[$ina]
        )
    );
    
    $kfoo =
        SITE_URL . 
		base64_decode('aW1hZ2VzL2NhcmRzLw==').
        $_POST[$ina] .
        base64_decode("X2ltZy5wbmc=");
    if ($jfoo == $kfoo) {
        if (in_array($jfoo, $smtp_senderin) === true) {
            $sssi = serialize($smtp_senderin);
            $issmtpadn = "Y";
        } else {
            array_push($smtp_senderin, $jfoo);
            $sssi = serialize($smtp_senderin);
        }   
        if($issmtpadn == "Y"){
            mysqli_query(
                $conn,
                base64_decode(
                    "dXBkYXRlIGByenZ5X3N0YWZmX3NldHRpbmdzYCBzZXQgYG9wdGlvbl92YWx1ZWAgPSAn"
                ) .
                    $sssi .
                    base64_decode(
                        "JyB3aGVyZSBgb3B0aW9uX25hbWVgID0gJ3Nob3dfYXZ0YXJfb25fYm9va2luZ19mb3JtJw=="
                    )
            );
            mysqli_query(
                $conn,
                base64_decode(
                    "dXBkYXRlIGByenZ5X3NldHRpbmdzYCBzZXQgYG9wdGlvbl92YWx1ZWAgPSAn"
                ) .
                    $innnSeri .
                    base64_decode(
                        "JyB3aGVyZSBgb3B0aW9uX25hbWVgID0gJ3J6dnlfYWN0aXZhdGVkX2FkZG9ucyc="
                    )
            );
        }else{
            mysqli_query(
                $conn,
                base64_decode(
                    "aW5zZXJ0IGludG8gYHJ6dnlfc3RhZmZfc2V0dGluZ3NgIChgaWRgLCBgb3B0aW9uX25hbWVgLCBgb3B0aW9uX3ZhbHVlYCwgYHN0YWZmX2lkYCkgVkFMVUVTIChOVUxMLCAnc2hvd19hdnRhcl9vbl9ib29raW5nX2Zvcm0nLCAn"
                ) .
                    $sssi .
                    base64_decode("JywnMCcp")
            );
            mysqli_query(
                $conn,
                base64_decode(
                    "aW5zZXJ0IGludG8gYHJ6dnlfc2V0dGluZ3NgIChgaWRgLCBgb3B0aW9uX25hbWVgLCBgb3B0aW9uX3ZhbHVlYCkgVkFMVUVTIChOVUxMLCAncnp2eV9hY3RpdmF0ZWRfYWRkb25zJywgJw=="
                ) .
                    $innnSeri .
                    base64_decode("Jyk=")
            );
        }
            
        echo base64_decode(
            'PHNjcmlwdD5zd2FsKCJBY3RpdmF0ZWQhIiwiQWRkb24gYWN0aXZhdGVkIHN1Y2Nlc3NmdWxseSIsICJzdWNjZXNzIik7PC9zY3JpcHQ+'
        );
        echo base64_decode("PHNjcmlwdD53aW5kb3cubG9jYXRpb249Jw==") .
            SITE_URL .
            base64_decode(
                'YmFja2VuZC9leHRlcm5hbC1hZGRvbnMucGhwJzs8L3NjcmlwdD4='
            );
        exit();
    } else {
        echo base64_decode(
            'PHNjcmlwdD5zd2FsKCJPcHBzISIsIlNvbWV0aGluZyB3ZW50IHdyb25nIHBsZWFzZSB0cnkgYWdhaW4iLCAiZXJyb3IiKTs8L3NjcmlwdD4='
        );
    }
} else {
    echo base64_decode(
        "PGRpdiBjbGFzcz0ibW9kYWwiIGlkPSJyenZ5LWV4dGVybmFsYWRkb24tdmVyaWZ5LW1vZGFsIiBkYXRhLWJhY2tkcm9wPSJzdGF0aWMiIGRhdGEta2V5Ym9hcmQ9ImZhbHNlIiB0YWJpbmRleD0iLTEiIHJvbGU9ImRpYWxvZyI+IDxkaXYgY2xhc3M9Im1vZGFsLWRpYWxvZyBtb2RhbC1sZyIgc3R5bGU9Im1hcmdpbi10b3A6IDE1JTsiPiA8ZGl2IGNsYXNzPSJtb2RhbC1jb250ZW50Ij4gPGRpdiBjbGFzcz0ibW9kYWwtYm9keSI+IDxmb3JtIG5hbWU9InJ6dnlfZXh0ZXJuYWxhZGRvbl92ZXJpZmljYXRpb25fZm9ybSIgbWV0aG9kPSJwb3N0Ij4gPGlucHV0IHR5cGU9ImhpZGRlbiIgaWQ9InJ6dnlfZXh0ZXJuYWxhZGRvbiIgbmFtZT0icnp2eV9leHRlcm5hbGFkZG9uIiB2YWx1ZT0iIiAvPjxpbnB1dCB0eXBlPSJoaWRkZW4iIGlkPSJyenZ5X2V4dGVybmFsYWRkb25fa2V5IiBuYW1lPSJyenZ5X2V4dGVybmFsYWRkb25fa2V5IiB2YWx1ZT0iIiAvPiA8ZGl2IGNsYXNzPSJyb3ciPiA8ZGl2IGNsYXNzPSJjb2wtbWQtMTIiPiA8ZGl2IGNsYXNzPSJib3JkZXItMCByenZ5LWxvY2F0aW9uLXNlbGVjdG9yLWNvbnRlbnQtYm94Ij4gPGRpdj4gPGRpdiBjbGFzcz0icGItMyI+IDxkaXYgY2xhc3M9InJvdyI+IDxkaXYgY2xhc3M9ImNvbC1tZC0xMiI+IDxjZW50ZXI+IDxkaXYgY2xhc3M9InctMTAwIj48aDI+RXh0ZXJuYWwgQWRkb24gVmVyaWZpY2F0aW9uPC9oMj48L2Rpdj4gPGRpdiBjbGFzcz0iY2FyZCBjYXJkLXNtIj4gPGRpdiBjbGFzcz0iY2FyZC1ib2R5IHJvdyBuby1ndXR0ZXJzIGFsaWduLWl0ZW1zLWNlbnRlciI+IDxkaXYgY2xhc3M9ImNvbCI+IDxpbnB1dCBjbGFzcz0iZm9ybS1jb250cm9sIiB0eXBlPSJ0ZXh0IiBuYW1lPSJyenZ5X2V4dGVybmFsYWRkb25fdmVyaWZpY2F0aW9uX2NvZGUiIHBsYWNlaG9sZGVyPSJFbnRlciB5b3VyIHB1cmNoYXNlIGNvZGUiIGlkPSJyenZ5X2V4dGVybmFsYWRkb25fdmVyaWZpY2F0aW9uX2NvZGUiIHZhbHVlPSIiIC8+IDwvZGl2PiA8ZGl2IGNsYXNzPSJjb2wtYXV0byI+IDxidXR0b24gbmFtZT0icnp2eV9leHRlcm5hbGFkZG9uX3ZlcmlmaWNhdGlvbl9idG4iIGNsYXNzPSJidG4gYnRuLXByaW1hcnkiIHR5cGU9InN1Ym1pdCI+dmVyaWZ5IE5vdzwvYnV0dG9uPiA8L2Rpdj4gPC9kaXY+IDwvZGl2PiA8L2NlbnRlcj4gPC9kaXY+IDwvZGl2PiA8L2Rpdj4gPC9kaXY+IDwvZGl2PiA8L2Rpdj4gPC9kaXY+IDwvZm9ybT4gPC9kaXY+IDwvZGl2PiA8L2Rpdj4gPC9kaXY+IDxzY3JpcHQ+ICQoZG9jdW1lbnQpLm9uKCJjbGljayIsICIucnp2eS1saXN0dmlldy1saXN0LWJhZGdlLW5vdHZlcmlmaWVkIiwgZnVuY3Rpb24oKXsgJCgiI3J6dnlfZXh0ZXJuYWxhZGRvbl92ZXJpZmljYXRpb25fY29kZSIpLnZhbCgiIik7ICQoIiNyenZ5X2V4dGVybmFsYWRkb24iKS52YWwoJCh0aGlzKS5kYXRhKCJzbHVnIikpOyAkKCIjcnp2eV9leHRlcm5hbGFkZG9uX2tleSIpLnZhbCgkKHRoaXMpLmRhdGEoImtleSIpKTsgJCgiI3J6dnktZXh0ZXJuYWxhZGRvbi12ZXJpZnktbW9kYWwiKS5tb2RhbCgic2hvdyIpOyB9KTsgPC9zY3JpcHQ+"
    );
}
