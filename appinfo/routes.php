<?php

declare(strict_types=1);
/**
 * Adminly Clients
 *
 * @copyright Copyright (C) 2020  Igor Oliveira <igor.oliveira@metaprovide.org>
 *
 * @author  Igor Oliveira <igor.oliveira@metaprovide.org>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

return ['routes' => [
	['name' => 'page#index', 'url' => '/client/{clientId}', 'verb' => 'GET', 'postfix' => 'direct.new'],
	['name' => 'page#index', 'url' => '/new-client', 'verb' => 'GET', 'postfix' => 'direct.new'],
	['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],
	['name' => 'page#create', 'url' => '/create', 'verb' => 'POST'],
	['name' => 'page#update', 'url' => '/update', 'verb' => 'POST'],
	['name' => 'page#getClient', 'url' => '/getClient', 'verb' => 'GET'],
	['name' => 'page#get', 'url' => '/get', 'verb' => 'GET'],
	['name' => 'page#getPage', 'url' => '/getPage', 'verb' => 'GET'],
	['name' => 'page#getNumberOfClients', 'url' => '/totalClients', 'verb' => 'GET'],
	['name' => 'page#searchByName', 'url' => '/searchByName', 'verb' => 'GET'],
	['name' => 'page#delete', 'url' => '/delete', 'verb' => 'POST'],
	['name' => 'page#getClientSessions', 'url' => '/client/{clientId}/sessions', 'verb' => 'GET'],
	['name' => 'page#getClientNextSession', 'url' => '/client/{clientId}/nextSession', 'verb' => 'GET'],
	['name' => 'page#getClientLastSession', 'url' => '/client/{clientId}/lastSession', 'verb' => 'GET'],
]];
