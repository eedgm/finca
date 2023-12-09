@props(['button', 'area' => '.print-area', 'size' => 'letter', 'orientation' => 'portrait'])

{{-- Example of button to usage --}}
{{-- <button
    id="print-button"
    class="focus:outline-none text-white bg-green-700 hover:bg-green-800 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 disabled:bg-green-200 disabled:text-green-400"
    wire:click="printTemplate"
>
    <i class='bx bxs-printer'></i>
    Imprimir
</button> --}}

@push('scripts')
    <script>
        let print_button = document.querySelector('#{{ $button }}');
        print_button.addEventListener('click', function(e) {
            e.preventDefault();
            let content = document.querySelector('{{ $area }}').innerHTML;
            let a = window.open('', '_blank');
            a.document.write('<html>');
            a.document.write('<style type="text/css" media="print">@media print {@page { size: {{ $size }} {{ $orientation }}; margin: 0; } body { margin: 1.6cm; }  header,footer {display: none !important;}}}</style>');
            a.document.write('<body>');
            a.document.write(content);
            a.document.write('</body></html>');
            a.document.close();
            a.print();

            setTimeout(function() {
                a.close();
            }, 1000);
        });

    </script>
@endpush
