<?php
/** @var MediaCollection $items */

use App\Enums\MediaConversion;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;

$max = $items->count() - 1;
?>
<script>
    let xdata = {
        current_id: 0,
        max: {{$max}},
        nextSlide() {
            if (this.current_id >= this.max) {
                return;
            }
            let activeSlide = document.querySelector('.slide.translate-x-0');
            activeSlide.classList.remove('translate-x-0');
            activeSlide.classList.add('-translate-x-full');

            let nextSlide = activeSlide.nextElementSibling;
            nextSlide.classList.remove('translate-x-full');
            nextSlide.classList.add('translate-x-0');

            this.current_id++;
        },
        prevSlide() {
            if (this.current_id <= 0) {
                return;
            }
            let activeSlide = document.querySelector('.slide.translate-x-0');
            activeSlide.classList.remove('translate-x-0');
            activeSlide.classList.add('translate-x-full');

            let previousSlide = activeSlide.previousElementSibling;
            previousSlide.classList.remove('-translate-x-full');
            previousSlide.classList.add('translate-x-0');

            this.current_id--;
        },
        selectSlide(selectedId) {
            let activeSlide = document.querySelector('.slide.translate-x-0');
            activeSlide.classList.remove('translate-x-0');

            document.querySelectorAll(".slide").forEach(function (slide) {
                let slideId = slide.getAttribute('data-id');
                if (slideId < selectedId) {
                    slide.classList.add('-translate-x-full');
                    slide.classList.remove('translate-x-full');
                }

                if (slideId > selectedId) {
                    slide.classList.add('translate-x-full');
                    slide.classList.remove('-translate-x-full');
                }

                if (slideId == selectedId) {
                    slide.classList.add('translate-x-0');
                    slide.classList.remove('translate-x-full');
                    slide.classList.remove('-translate-x-full');
                }
            });
        }
    };
</script>
<div class="md:w-3/5 bg-cover mb-8 md:mb-0 lg:border-r lg:border-gray-200" x-data="xdata">
    <div class="mb-10 relative w-full">

        <div class="slider">
            <div class="slider-inner relative overflow-hidden" style="height: 500px">
                <div class="h-full w-full">
                    <ul class="w-full">
                        @foreach ($items as $i => $media)
                            <li class="absolute inset-0 transition-all ease-in-out duration-1000 transform {{$i == 0 ? "translate-x-0" : "translate-x-full"}} slide"
                                data-id="{{$i}}">
                                <img src="{{$media->getUrl()}}" class="w-auto h-full mx-auto"/>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <span @click="prevSlide()" x-show="current_id > 0"
              class="absolute h-screen top-1/2 left-0 ml-8 transform translate-1/2 cursor-pointer">
            <x-icon.left/>
        </span>

        <span @click="nextSlide()" x-show="current_id < max"
              class="absolute h-screen top-1/2 right-0 mr-8 transform translate-1/2 cursor-pointer">
            <x-icon.right/>
        </span>
    </div>
    <div class="flex flex-wrap">
        @foreach ($items as $i => $media)
            <div class="mx-1">
                <a class="block cursor-pointer" @click="selectSlide({{$i}})">
                    <img class="w-auto h-32 object-cover mx-auto" src="{{$media->getUrl(MediaConversion::thumb())}}"
                         alt="">
                </a>
            </div>
        @endforeach
    </div>
</div>
