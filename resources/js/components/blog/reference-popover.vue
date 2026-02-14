<script setup lang="ts">
import { ref, computed } from 'vue'
import type { IPost } from '@/types/models/post'

const props = defineProps<{
    references: IPost['references']
}>()

defineExpose({
    handleMouseOver
})

const isOpen = ref(false)
const activeReferenceId = ref<number | null>(null)
const activeTerm = ref<string | null>(null)
const triggerElement = ref<HTMLElement | null>(null)
let closeTimeout: ReturnType<typeof setTimeout> | null = null

const activeReferenceData = computed(() => {
    if (!activeReferenceId.value) return null
    return props.references?.find(r => r.id == activeReferenceId.value && r.pivot.term == activeTerm.value)
})

const virtualReference = computed(() => {
    if (!triggerElement.value) return undefined

    return {
        getBoundingClientRect: () => triggerElement.value!.getBoundingClientRect()
    }
})

function handleMouseOver(event: MouseEvent) {
    const target = event.target as HTMLElement

    const referenceSpan = target.closest('span[data-reference-id]') as HTMLElement

    if (referenceSpan) {
        if (closeTimeout) clearTimeout(closeTimeout)

        activeReferenceId.value = Number(referenceSpan.dataset.referenceId)
        activeTerm.value = referenceSpan.dataset.term
        triggerElement.value = referenceSpan
        isOpen.value = true
    } else {
        scheduleClose()
    }
}

function scheduleClose() {
    if (closeTimeout) clearTimeout(closeTimeout)

    closeTimeout = setTimeout(() => {
        isOpen.value = false
        activeReferenceId.value = null
        triggerElement.value = null
    }, 200)
}

function onPopoverEnter() {
    if (closeTimeout) clearTimeout(closeTimeout)
}

function onPopoverLeave() {
    scheduleClose()
}
</script>

<template>
    <UPopover :open="isOpen" :reference="virtualReference" :open-delay="0" :close-delay="0" @mouseenter="onPopoverEnter"
        @mouseleave="onPopoverLeave">

        <template #content>
            <div class="w-80 space-y-3 p-4" v-if="activeReferenceData">
                <header>
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                        <h3 class="text-base font-semibold text-default">
                            <p>Titulo: {{ activeReferenceData.title }} </p>
                        </h3>
                        <p class="text-xs text-muted mt-1">
                            <span>Descrição: {{ activeReferenceData.description }}</span>
                        </p>
                    </div>
                </header>

                <section name="context-section">
                    <div class="space-y-2">
                        <p class="text-xs font-medium text-muted uppercase tracking-wide">
                            Contexto:
                        </p>
                        <div class="bg-accented rounded-md p-3 border border-gray-200 dark:border-gray-700">
                            <p class="text-sm text-default leading-relaxed">
                                {{ activeReferenceData.pivot.context }}
                            </p>
                        </div>
                    </div>
                </section>

                <section name="term-metadata-section">
                    <div class="space-y-2 pt-2">
                        <div class="flex items-center gap-2">
                            <UIcon name="i-lucide-tag" class="h-3.5 w-3.5 text-muted" />
                            <span class="text-xs font-medium text-muted">Termo:</span>
                            <UBadge variant="subtle" color="primary" size="sm">
                                {{ activeReferenceData.pivot.term }}
                            </UBadge>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-muted">
                            <UIcon name="i-lucide-calendar" class="h-3.5 w-3.5" />
                            <span>{{ new
                                Date(activeReferenceData.pivot.created_at).toLocaleDateString('pt-BR')
                                }}</span>
                        </div>
                    </div>
                </section>

            </div>
        </template>
    </UPopover>
</template>