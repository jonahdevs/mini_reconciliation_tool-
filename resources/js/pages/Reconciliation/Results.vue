<script setup>
import ResultGroup from '@/components/ResultGroup.vue';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Head, Link, router } from '@inertiajs/vue3';
import { ArrowLeft, BadgeCheck, CircleX, Shuffle, TriangleAlert } from 'lucide-vue-next';
// Your code goes here
const props = defineProps({
    matched: Object,
    internalOnly: Object,
    providerOnly: Object,
    mismatched: Object,
})

const exportCsv = (type) => {
    window.location.href = `/export/${type}`;
}
</script>

<template>

    <Head title="Reconciliation Results" />
    <section class="container mx-auto max-7xl">
        <div class="py-5">
            <Link href="#" class="inline-flex items-center gap-2">
            <ArrowLeft class="size-5" />
            Back
            </Link>
        </div>
        <div class="py-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="bg-accent text-accent-foreground rounded-lg p-6 shadow-sm flex items-start gap-3">
                <div class="grid place-items-center rounded-lg p-2 bg-background text-background-foreground">
                    <BadgeCheck class="size-6 text-inherit" />
                </div>
                <div>
                    <h2 class="text-base font-medium mb-2">Matched Transactions</h2>
                    <p class="text-lg">{{ matched.length }} transactions</p>
                </div>
            </div>

            <div class="bg-accent text-accent-foreground rounded-lg p-6 shadow-sm flex items-start gap-3">
                <div class="grid place-items-center rounded-lg p-2 bg-background text-background-foreground">
                    <TriangleAlert class="size-6 text-inherit" />
                </div>
                <div>

                    <h2 class="text-base font-medium mb-2"> Internal Only Transactions</h2>
                    <p class="text-lg">{{ internalOnly.length }} transactions</p>
                </div>
            </div>

            <div class="bg-accent text-accent-foreground rounded-lg p-6 shadow-sm flex items-start gap-3">
                <div class="grid place-items-center rounded-lg p-2 bg-background text-background-foreground">
                    <CircleX class="size-6 text-inherit" />
                </div>

                <div>

                    <h2 class="text-base font-medium mb-2">Provider Only Transactions</h2>
                    <p class="text-lg">{{ providerOnly.length }} transactions</p>
                </div>
            </div>

            <div class="bg-accent text-accent-foreground rounded-lg p-6 shadow-sm flex items-start gap-3">
                <div class="grid place-items-center rounded-lg p-2 bg-background text-background-foreground">
                    <Shuffle class="size-6 text-inherit" />
                </div>
                <div>

                    <h2 class="text-base font-medium mb-2">Mismatched Transactions</h2>
                    <p class="text-lg">{{ mismatched.length }} transactions</p>
                </div>
            </div>
        </div>


        <section>
            <Tabs default-value="matched" class="w-full">
                <TabsList>
                    <TabsTrigger value="matched">
                        Matched
                    </TabsTrigger>
                    <TabsTrigger value="internal">
                        Internal Only
                    </TabsTrigger>

                    <TabsTrigger value="provider">
                        Provider Only
                    </TabsTrigger>

                    <TabsTrigger value="mismatched">
                        Mismatched
                    </TabsTrigger>
                </TabsList>
                <TabsContent value="matched">
                    <div>
                        <div class="flex justify-between items-center my-4">
                            <h2 class="text-lg font-semibold">Matched ({{ matched.length }})</h2>


                            <Button variant="link" @click="exportCsv('matched')">
                                Export CSV
                            </Button>
                        </div>

                        <Table>
                            <TableCaption>A list of matching transactions.</TableCaption>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>ID</TableHead>
                                    <TableHead>Reference</TableHead>
                                    <TableHead>Amount</TableHead>
                                    <TableHead>Status</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="transaction in matched" :key="transaction.id">
                                    <TableCell>{{ transaction.id }}</TableCell>
                                    <TableCell>{{ transaction.transaction_reference }}</TableCell>
                                    <TableCell>{{ transaction.amount }}</TableCell>
                                    <TableCell>{{ transaction.status }}</TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>

                </TabsContent>
                <TabsContent value="internal">
                    <div class="flex justify-between items-center my-4">
                        <h2 class="text-lg font-semibold">Internal Only ({{ internalOnly.length }})</h2>


                        <Button variant="link" @click="exportCsv('internal')">
                            Export CSV
                        </Button>
                    </div>

                    <div>
                        <Table>
                            <TableCaption>A list of internal only transactions.</TableCaption>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>ID</TableHead>
                                    <TableHead>Reference</TableHead>
                                    <TableHead>Amount</TableHead>
                                    <TableHead>Status</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="transaction in internalOnly" :key="transaction.id">
                                    <TableCell>{{ transaction.id }}</TableCell>
                                    <TableCell>{{ transaction.transaction_reference }}</TableCell>
                                    <TableCell>{{ transaction.amount }}</TableCell>
                                    <TableCell>{{ transaction.status }}</TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </TabsContent>

                <TabsContent value="provider">

                    <div>
                        <div class="flex justify-between items-center my-4">
                            <h2 class="text-lg font-semibold">Provider Only ({{ providerOnly.length }})</h2>

                            <Button variant="link" @click="exportCsv('provider')">
                                Export CSV
                            </Button>
                        </div>

                        <Table>
                            <TableCaption>A list of provider only transactions.</TableCaption>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>ID</TableHead>
                                    <TableHead>Reference</TableHead>
                                    <TableHead>Amount</TableHead>
                                    <TableHead>Status</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="transaction in providerOnly" :key="transaction.id">
                                    <TableCell>{{ transaction.id }}</TableCell>
                                    <TableCell>{{ transaction.transaction_reference }}</TableCell>
                                    <TableCell>{{ transaction.amount }}</TableCell>
                                    <TableCell>{{ transaction.status }}</TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </TabsContent>

                <TabsContent value="mismatched">
                    <div>
                        <div class="flex justify-between items-center my-4">
                            <h2 class="text-lg font-semibold">Mismatched ({{ mismatched.length }})</h2>

                            <Button variant="link" @click="exportCsv('mismatched')">
                                Export CSV
                            </Button>
                        </div>

                        <Table>
                            <TableCaption>A list of mismatched transactions.</TableCaption>
                            <TableHeader>
                                <TableRow class="divide-x">
                                    <TableHead colspan="3" class="text-center">Internal</TableHead>
                                    <TableHead colspan="3" class="text-center">Provider</TableHead>
                                </TableRow>
                                <TableRow class="bg-muted text-muted-foreground">
                                    <TableHead>Reference</TableHead>
                                    <TableHead>Amount</TableHead>
                                    <TableHead class="border-e">Status</TableHead>
                                    <TableHead>Reference</TableHead>
                                    <TableHead>Amount</TableHead>
                                    <TableHead>Status</TableHead>
                                </TableRow>
                            </TableHeader>

                            <TableBody>
                                <TableRow v-for="(item, index) in mismatched" :key="index">
                                    <!-- Internal -->
                                    <TableCell class="text-red-600 font-mono">{{ item.internal.transaction_reference }}
                                    </TableCell>
                                    <TableCell :class="{
                                        'text-red-600': true,
                                        'bg-yellow-100 text-yellow-800 font-semibold': item.internal.amount !== item.provider.amount
                                    }">
                                        {{ item.internal.amount }}
                                    </TableCell>
                                    <TableCell class="border-e" :class="{
                                        'text-red-600': true,
                                        'bg-red-100 text-red-800 font-semibold': item.internal.status !== item.provider.status
                                    }">
                                        {{ item.internal.status }}
                                    </TableCell>

                                    <!-- Provider -->
                                    <TableCell class="text-blue-600 font-mono">{{ item.provider.transaction_reference }}
                                    </TableCell>
                                    <TableCell :class="{
                                        'text-blue-600': true,
                                        'bg-yellow-100 text-yellow-800 font-semibold': item.provider.amount !== item.internal.amount
                                    }">
                                        {{ item.provider.amount }}
                                    </TableCell>
                                    <TableCell :class="{
                                        'text-blue-600': true,
                                        'bg-red-100 text-red-800 font-semibold': item.provider.status !== item.internal.status
                                    }">
                                        {{ item.provider.status }}
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </TabsContent>
            </Tabs>
        </section>
    </section>


</template>

<style scoped></style>
