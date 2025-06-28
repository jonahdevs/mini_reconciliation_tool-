<script setup>
import { Table, TableHeader, TableRow, TableHead, TableBody, TableCell } from '@/components/ui/table'

// Your code goes here
const props = defineProps({
    title: String,
    data: Array,
    type: String
})

const headers = props.data?.length
    ? Object.keys(props.type === 'mismatched' ? props.data[0]?.internal || {} : props.data[0])
    : []
</script>

<template>
    <div>
        <div class="flex justify-between items-center">
            <h2 class="text-lg font-semibold">{{ title }} ({{ data?.length || 0 }})</h2>
            <a :href="`/reconciliation/export/${type}`" class="text-blue-500 text-sm underline">Export CSV</a>
        </div>

        <Table v-if="data && data.length" class="mt-4">
            <TableHeader>
                <TableRow>
                    <TableHead v-for="key in headers" :key="key">{{ key }} </TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <template v-if="type !== 'mismatched'">
                    <TableRow v-for="(row, i) in data" :key="i">
                        <TableCell v-for="key in headers" :key="key">{{ row[key] }} </TableCell>
                    </TableRow>
                </template>

                <template v-else>
                    <TableRow v-for="(pair, i) in data" :key="i">
                        <TableCell colspan="100%">
                            <div class="text-sm bg-yellow-50 p-2 rounded">
                                <strong>Ref:</strong> {{ pair.internal.transaction_reference }}<br>
                                <strong>Amount:</strong> Internal {{ pair.internal.amount }} vs Provider {{
                                    pair.provider.amount }}<br>
                                <strong>Status:</strong> Internal {{ pair.internal.status }} vs Provider {{
                                    pair.provider.status }}
                            </div>
                        </TableCell>
                    </TableRow>
                </template>
            </TableBody>
        </Table>
    </div>
</template>

<style scoped></style>
