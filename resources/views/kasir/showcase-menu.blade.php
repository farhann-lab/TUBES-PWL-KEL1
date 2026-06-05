@extends('layouts.kasir')

@section('content')

<div
    x-data="{
        active: 0,
        menus: [
            {
                name:'Kopi Susu',
                image:'/images/menu/menu1.jpg',
                desc:'Lorem ipsum dolor sit amet.'
            },
            {
                name:'Americano',
                image:'/images/menu/menu2.jpg',
                desc:'Lorem ipsum dolor sit amet.'
            },
            {
                name:'Matcha',
                image:'/images/menu/menu3.jpg',
                desc:'Lorem ipsum dolor sit amet.'
            },
            {
                name:'Croissant',
                image:'/images/menu/menu4.jpg',
                desc:'Lorem ipsum dolor sit amet.'
            }
        ]
    }"
    class="p-8"
>

    <h1 class="text-4xl font-bold mb-10">
        Showcase Menu
    </h1>

    <div class="grid lg:grid-cols-2 gap-10">

        <div class="space-y-4">

            <template
                x-for="(menu,index) in menus"
                :key="index"
            >

                <button
                    @click="active=index"
                    class="w-full text-left p-5 rounded-xl border hover:bg-[#F6F3F0]"
                >

                    <span
                        x-text="menu.name"
                        class="font-semibold"
                    ></span>

                </button>

            </template>

        </div>

        <div class="bg-white rounded-3xl shadow-xl overflow-hidden">

            <img
                :src="menus[active].image"
                class="w-full h-96 object-cover"
            >

            <div class="p-8">

                <h2
                    x-text="menus[active].name"
                    class="text-3xl font-bold mb-4"
                ></h2>

                <p
                    x-text="menus[active].desc"
                    class="text-gray-600"
                ></p>

            </div>

        </div>

    </div>

</div>

@endsection