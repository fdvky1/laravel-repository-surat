<?php
$imagePath = public_path('storage/' . $config->image_name);
$imageData = file_get_contents($imagePath);
$base64Image = base64_encode($imageData);

?>
<div style="width: 100%; margin-left: auto; margin-right: auto; font-family: sans-serif;">
    <header style="width: 100%;">
        <table align="center">
            <tr>
                <td>
                    <img width="80" height="80" src="data:image/png;base64,{{ $base64Image }}" alt="Header">
                </td>
                <td>
                    <div align="center">
                        <h2 style="margin: 0px;">{{ $config->header }}</h2>
                        <h3 style="margin: 0px;">{{ $config->subheader }}</h3>
                        <span style="font-size: small;">{{ $config->address }}</span>
                        <br>
                        <span style="font-size: small;">{{ $config->contact }}</span>
                    </div>
                </td>
            </tr>
        </table>
        <hr>
    </header>
    <section>
        <table style="width: 100%;">
            <tr>
                <td style="width: 75%;">
                    <table style="font-size: medium;">
                        <tr>
                            <td>Nomor</td>
                            <td>:</td>
                            <td>{{ $data->status == 'published' ? $data->letter_number : '-' }}/{{ $data->classification_code }}/{{ $data->month }}/{{ $data->year }}</td>
                        </tr>
                        @if(count($data->attachments))
                        <tr>
                            <td>Lampiran</td>
                            <td>:</td>
                            <td>{{count($data->attachments)}} Lampiran</td>
                        </tr>
                        @endif
                        <tr>
                            <td>Perihal</td>
                            <td>:</td>
                            <td>{{ $data->regarding }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 25%;">
                    <div>
                        {{ explode(',', $data->formatted_letter_date)[1] }}
                    </div>
                </td>
            </tr>
        </table>
    </section>
    <section>
        <table>
            <tr>
                <td>Kepada Yth.</td>
            </tr>
            @foreach(explode(",", $data->to) as $to)
            <tr>
                <td>{{ $to }}</td>
            </tr>
            @endforeach
        </table>
    </section>
    <section>
        {!! $data->content !!}
    </section>
    <section>
        <table style="width: 100%;">
            <tr>
                <td style="width: 70%;" valign="top">
                    Demikian surat ini kami sampaikan. sekian dan terima kasih.
                </td>
                <td style="width: 30%;">
                    <div align="center">
                        <p>{{ $config->position_name }}</p>
                        <div style="height: 40px; width: 40px;">

                        </div>
                        <p><b>{{ $config->name }}</b></p>
                    </div>
                </td>
            </tr>
        </table>
    </section>
</div>
