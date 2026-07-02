@extends('layouts.app')

@section('title', 'Coffee Experience - Learn About Coffee')

@section('content')

<!-- ================= HERO SECTION ================= -->

<section class="relative min-h-screen bg-[#faf8f5] flex items-center overflow-hidden">

    <div class="absolute text-[25vw] font-black text-amber-900/[0.04] left-0">
        COFFEE
    </div>


    <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-10 items-center relative z-10">

        <div>

            <p class="text-xs tracking-[0.4em] uppercase text-amber-700 mb-6">
                Premium Coffee Experience
            </p>


            <h1 class="text-5xl md:text-7xl font-black text-amber-950 leading-tight">
                Nikmati
                <br>
                Secangkir Kopi
                <br>
                Terbaik
            </h1>


            <p class="mt-8 text-gray-600 leading-relaxed max-w-lg">
                Temukan perjalanan kopi mulai dari biji pilihan,
                proses roasting, hingga menjadi minuman kopi
                dengan aroma dan rasa yang sempurna.
            </p>


            <div class="mt-10 flex gap-5">

                <a href="/products"
                class="bg-amber-800 text-white px-8 py-4 rounded-xl text-sm font-bold uppercase tracking-widest hover:bg-amber-900 transition">
                    Belanja Kopi
                </a>


                <a href="#about"
                class="border-b-2 border-amber-800 py-4 text-sm font-bold uppercase tracking-widest text-amber-900">
                    Pelajari Kopi
                </a>

            </div>

        </div>


        <div class="flex justify-center">

            <img
            src="https://images.unsplash.com/photo-1495474472203-4ad7c1a5a5b7?auto=format&fit=crop&w=900&q=80"
            class="rounded-t-full shadow-2xl w-[80%] h-[650px] object-cover">


        </div>

    </div>

</section>




<!-- ================= ABOUT COFFEE ================= -->


<section id="about" class="py-24 bg-white">


<div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-14 items-center">


<div>


<p class="text-xs uppercase tracking-[0.3em] text-amber-700">
Tentang Kopi
</p>


<h2 class="text-4xl font-black text-amber-950 mt-4">
Apa Itu Kopi?
</h2>


<p class="mt-6 text-gray-600 leading-relaxed">

Kopi merupakan minuman yang berasal dari biji tanaman kopi
yang telah melalui proses panjang mulai dari penanaman,
pemanenan, pengolahan, pengeringan, hingga proses roasting.

Setiap jenis kopi memiliki karakter rasa yang berbeda,
tergantung dari daerah asal, ketinggian tempat tumbuh,
jenis tanaman, dan metode pengolahannya.

</p>


<p class="mt-5 text-gray-600 leading-relaxed">

Indonesia menjadi salah satu negara penghasil kopi terbaik
di dunia dengan berbagai daerah penghasil kopi terkenal
seperti Aceh Gayo, Toraja, Jawa Barat, Bali, dan Flores.

</p>


</div>



<div>

<img
src="https://images.unsplash.com/photo-1447933601403-0c6688de566e?auto=format&fit=crop&w=900&q=80"
class="rounded-3xl shadow-xl">


</div>


</div>

</section>





<!-- ================= JENIS KOPI ================= -->


<section class="py-24 bg-amber-950 text-white">


<div class="max-w-7xl mx-auto px-6">


<div class="text-center mb-16">

<p class="uppercase text-xs tracking-[0.4em] text-amber-300">
Coffee Beans
</p>


<h2 class="text-4xl font-black mt-4">
Jenis-Jenis Biji Kopi
</h2>


</div>



<div class="grid md:grid-cols-4 gap-8">



<!-- Arabica -->

<div class="bg-white/10 rounded-2xl p-6 backdrop-blur">


<img
src="https://images.unsplash.com/photo-1512568400610-62da28bc8a13?auto=format&fit=crop&w=500&q=80"
class="rounded-xl h-52 w-full object-cover">


<h3 class="text-xl font-bold mt-5">
Arabica
</h3>


<p class="text-sm text-amber-100/70 mt-3 leading-relaxed">

Memiliki rasa lebih halus,
aroma kompleks, tingkat keasaman lebih tinggi,
dan sering digunakan untuk specialty coffee.

</p>


</div>





<!-- Robusta -->


<div class="bg-white/10 rounded-2xl p-6 backdrop-blur">


<img
src="https://images.unsplash.com/photo-1559056199-641a0ac8b55e?auto=format&fit=crop&w=500&q=80"
class="rounded-xl h-52 w-full object-cover">


<h3 class="text-xl font-bold mt-5">
Robusta
</h3>


<p class="text-sm text-amber-100/70 mt-3 leading-relaxed">

Memiliki rasa lebih kuat,
kadar kafein lebih tinggi,
dan karakter rasa pahit yang khas.

</p>


</div>





<!-- Liberica -->


<div class="bg-white/10 rounded-2xl p-6 backdrop-blur">


<img
src="https://images.unsplash.com/photo-1498804103079-a6351b050096?auto=format&fit=crop&w=500&q=80"
class="rounded-xl h-52 w-full object-cover">


<h3 class="text-xl font-bold mt-5">
Liberica
</h3>


<p class="text-sm text-amber-100/70 mt-3 leading-relaxed">

Memiliki ukuran biji lebih besar,
aroma unik seperti buah dan kayu,
serta cukup langka ditemukan.

</p>


</div>





<!-- Excelsa -->


<div class="bg-white/10 rounded-2xl p-6 backdrop-blur">


<img
src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=500&q=80"
class="rounded-xl h-52 w-full object-cover">


<h3 class="text-xl font-bold mt-5">
Excelsa
</h3>


<p class="text-sm text-amber-100/70 mt-3 leading-relaxed">

Memiliki rasa unik dengan perpaduan
asam, manis, dan aroma buah yang kuat.

</p>


</div>



</div>


</div>


</section>



@endsection