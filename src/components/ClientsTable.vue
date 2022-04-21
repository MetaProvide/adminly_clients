<template>
	<div class="centered">
		<table>
			<thead>
				<tr>
					<th v-for="col in columns" :key="col.label">{{ col }}</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="client in clients" :key="client.username">
					<td v-for="col in columns" :key="col.label">
						{{ client[col] }}
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</template>

<script>
export default {
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
			columns: ["name", "email", "description"],
		};
	},
};
</script>

<style scoped>
.centered {
	height: 100%;
	display: flex;
	justify-content: center;
	align-content: center;
}

table {
	font-family: "Open Sans", sans-serif;
	max-width: 900px;
	border-collapse: collapse;
	margin: 10px 10px 0 10px;
	box-shadow: 0px 0px 36px 15px rgba(0, 0, 0, 0.28);
	border-radius: 20px;
	border-style: hidden; /* hide standard table (collapsed) border */
}
table th {
	font-weight: bold;
	padding: 10px;
}
table td {
	text-align: left;
	padding: 8px;
}
table td:last-child {
	border-right: none;
}
</style>
