<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-3D-50">
        <div class="shadow-arriba-xs rounded-[30px] bg-white">
            <slot name="logo" />
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-3D-50 shadow-abajo-2 overflow-hidden sm:rounded-lg">
            <slot />
        </div>
    </div>
</template>
