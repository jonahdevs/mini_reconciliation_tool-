<script setup>
import { Head, useForm, usePage } from '@inertiajs/vue3';
import ResultGroup from '@/components/ResultGroup.vue';
import { Label } from "@/components/ui/label";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import InputError from '@/components/InputError.vue';
import { Checkbox } from "@/components/ui/checkbox";
import { ref } from 'vue';

// Your code goes here
const matched = usePage().props.flash.matched;
const internalOnly = usePage().props.flash.internalOnly;
const providerOnly = usePage().props.flash.providerOnly;
const mismatched = usePage().props.flash.mismatched;

const form = useForm({
    internal_file: null,
    provider_file: null,
    overwrite: false,
});

const submit = () => {
    form.post(route('reconciliation.upload'));
};
</script>

<template>

    <Head title="Reconciliation" />

    <div class="max-w-4xl mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Mini Reconciliation Tool</h1>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <Label for="internal_file">Internal File (CSV)</Label>
                <Input type="file" @change="e => form.internal_file = e.target.files[0]" />
                <InputError :message="form.errors.internal_file" />
            </div>
            <div>
                <Label for="provider_file">Provider File (CSV)</Label>
                <Input type="file" @change="e => form.provider_file = e.target.files[0]" />
                <InputError :message="form.errors.provider_file" />
            </div>

            <div class="flex items-center space-x-2">
                <Checkbox id="overwrite" v-model="form.overwrite" />
                <label for="overwrite"
                    class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                    Overwrite existing transactions
                </label>
            </div>
            <Button type="submit" :disabled="form.processing">
                {{ form.processing ? 'Reconciling...' : 'Start Reconciliation' }}
            </Button>
        </form>

        <div class="mt-8 space-y-6">
            <ResultGroup v-if="$page.props.flash.matched" title="✅ Matched Transactions"
                :data="$page.props.flash.matched" type="matched" />
            <ResultGroup v-if="$page.props.flash.internalOnly" title="⚠️ Present only in Internal file"
                :data="$page.props.flash.internalOnly" type="internalOnly" />
            <ResultGroup v-if="$page.props.flash.providerOnly" title="❌ Present only in Provider file"
                :data="$page.props.flash.providerOnly" type="providerOnly" />
            <ResultGroup v-if="$page.props.flash.mismatched" title="🔄 Mismatched Transactions"
                :data="$page.props.flash.mismatched" type="mismatched" />
        </div>
    </div>
</template>

<style scoped></style>
