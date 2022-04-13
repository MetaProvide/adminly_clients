<template>
	<main>
		<h1>Hello from clients</h1>
		<ul v-for="client in clients" :key="client.key" class="clients">
			<li>{{ client.name }}</li>
		</ul>
	</main>
</template>

<script>
export default {
	name: "App",
	props: {
		clients: {
			type: Array,
			default() {
				return [];
			},
		},
	},
};
</script>

<style scoped>
.clients {
	font-weight: bold;
}
</style>
