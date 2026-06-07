<script setup>
import { computed } from 'vue';

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['purchase', 'inquiry']);

const buttonLabel = computed(() => {
    return props.product.pricing_type === 'fixed' ? 'Beli Sekarang' : 'Tanya Harga via WA';
});

const imageAlt = computed(() => {
    return props.product.alt_text || props.product.name;
});

function formatPrice(price) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(price);
}

function handleAction() {
    if (props.product.pricing_type === 'fixed') {
        emit('purchase', props.product);
    } else {
        emit('inquiry', props.product);
    }
}
</script>

<template>
    <div
        class="bg-surface-container-low p-4 rounded-2xl border border-outline flex gap-4 hover:border-accent transition-colors group"
    >
        <div class="w-20 h-20 shrink-0 overflow-hidden rounded-xl">
            <img
                v-if="product.image_url"
                :src="product.image_url"
                :alt="imageAlt"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
            />
            <div
                v-else
                class="w-full h-full bg-surface-container-high flex items-center justify-center"
                aria-hidden="true"
            >
                <svg class="w-6 h-6 text-on-surface-variant" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        </div>

        <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-2">
                <h3 class="font-bold text-on-surface truncate">{{ product.name }}</h3>
                <span
                    v-if="product.tag"
                    class="text-xs px-2 py-0.5 rounded border border-accent text-accent font-bold uppercase shrink-0"
                >
                    {{ product.tag }}
                </span>
            </div>
            <p
                v-if="product.description"
                class="text-xs text-on-surface-variant mt-0.5 line-clamp-2"
            >
                {{ product.description }}
            </p>
            <p class="text-accent font-bold mt-1">
                {{ formatPrice(product.price) }}
            </p>

            <button
                @click="handleAction"
                :aria-label="buttonLabel + ' - ' + product.name"
                class="mt-2 inline-flex w-auto items-center px-4 py-2 text-sm rounded-lg bg-accent text-on-accent font-bold hover:brightness-110 active:scale-[0.98] transition-all focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-accent/30"
            >
                {{ buttonLabel }}
            </button>
        </div>
    </div>
</template>
