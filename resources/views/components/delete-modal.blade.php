@props(['id' => 'deleteModal'])

<div 
    x-data="{ show: false, actionUrl: '', title: '', message: '' }" 
    @open-delete-modal.window="
        if ($event.detail.id === '{{ $id }}') {
            show = true;
            actionUrl = $event.detail.actionUrl;
            title = $event.detail.title || 'Konfirmasi Hapus';
            message = $event.detail.message || 'Apakah Anda yakin ingin menghapus data ini?';
        }
    "
    x-show="show" 
    class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    style="display: none;"
>
    <div 
        @click.away="show = false" 
        class="bg-slate-900 border border-slate-800 rounded-3xl max-w-sm w-full p-6 shadow-2xl space-y-6"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    >
        <div class="flex items-start space-x-4">
            <div class="shrink-0 bg-red-500/10 p-3 rounded-full text-red-500">
                <i class="fa-solid fa-triangle-exclamation text-xl"></i>
            </div>
            <div>
                <h4 class="text-base font-bold text-white" x-text="title"></h4>
                <p class="text-sm text-slate-400 mt-1" x-text="message"></p>
            </div>
        </div>

        <div class="flex items-center space-x-3 justify-end pt-4 border-t border-slate-850">
            <button type="button" @click="show = false" class="text-slate-400 hover:text-white text-sm font-bold px-5 py-2.5 rounded-xl border border-slate-800 hover:bg-slate-800 transition">
                Batal
            </button>
            <form :action="actionUrl" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold text-sm px-5 py-2.5 rounded-xl transition">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>
