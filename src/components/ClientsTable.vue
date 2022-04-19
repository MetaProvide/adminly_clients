<template>
	<VueGoodTable
		:columns="columns"
		:rows="clients"
		:pagination-options="{
			enabled: true,
			mode: 'pages',
			pageLabel: 'page',
		}"
		:search-options="{
			enabled: true,
		}"
	/>
</template>

<script>
import "vue-good-table/dist/vue-good-table.css";
import { VueGoodTable } from "vue-good-table";

export default {
	components: {
		VueGoodTable,
	},
	props: {
		clients: {
			type: Array,
			default() {
				return [];
			},
		},
	},
	data() {
		return {
			columns: [
				{
					label: "Name",
					field: "name",
				},
				{
					label: "Email",
					field: "email",
				},
				{
					label: "Description",
					field: "description",
				},
			],
		};
	},
};
</script>
