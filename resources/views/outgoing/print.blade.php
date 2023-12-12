<div style="width: 100%; margin-left: auto; margin-right: auto; font-family: sans-serif;">
    <header style="width: 100%;">
        <table>
            <tr>
                <td>
                    <img src="https://ihestudies.org/wp-content/themes/lemmony-agency/assets/media/content/ihes.svg" alt="Header">
                </td>
                <td>
                    <div align="center">
                        <h2 style="margin: 0px;">KEMENTRIAN DAERAH REPUBLIK INDONESIA</h2>
                        <h3 style="margin: 0px;">KANTOR KEMENTRIAN KABUPATEN</h3>
                        <span style="font-size: small;">Jl. Baker No. 221B</span>
                        <br>
                        <span style="font-size: small;">Telp (123) 456 7890</span>
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
                        <tr>
                            <td>Lampiran</td>
                            <td>:</td>
                            <td>1</td>
                        </tr>
                        <tr>
                            <td>Perihal</td>
                            <td>:</td>
                            <td>{{ $data->summary }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 25%;">
                    <div>
                        {{ $data->formatted_letter_date }}
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
                        <p>Kepala</p>
                        <div style="height: 40px; width: 40px;">

                        </div>
                        <p><b>Dr. John H. Watson</b></p>
                    </div>
                </td>
            </tr>
        </table>
        <div style="width: 100%; display: flex; justify-content: space-between;">
            
        </div>
    </section>
</div>
