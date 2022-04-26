<template>
	<div>
		<table>
			<thead>
				<tr>
					<th>Client Name</th>
					<th>Email</th>
					<th>Description</th>
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
table {
	font-family: 'Open Sans', sans-serif;
	width: 100%;
	border-collapse: collapse;
	box-shadow: 0px 0px 9.68155px rgba(0, 0, 0, 0.15);
	border-radius: 15px;
	border-style: hidden; /* hide standard table (collapsed) border */
}

table th {
	font-weight: bold;
	padding: 10px 20px;
	border: 1 solid #eeeeee;
}

table td {
	text-align: left;
	padding: 10px 20px;
}

table tr {
	border-bottom: 1px solid #eeeeee;
}

table td:last-child {
	border-right: none;
}

table td:first-child {
	color: #238dda;
}
</style>
