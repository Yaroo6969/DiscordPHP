<?php

/*
 * This file is a part of the DiscordPHP project.
 *
 * Copyright (c) 2015-present David Cole <david.cole1340@gmail.com>
 *
 * This file is subject to the MIT license that is bundled
 * with this source code in the LICENSE.md file.
 */

namespace Discord\WebSockets\Events;

use Discord\WebSockets\Event;
use Discord\Parts\Guild\Guild;
use Discord\Parts\Guild\Integration;

/**
 * @link https://discord.com/developers/docs/topics/gateway#integration-create
 *
 * @since 7.0.0
 */
class IntegrationCreate extends Event
{
    /**
     * @inheritDoc
     */
    public function handle($data)
    {
        /** @var Integration */
        $integrationPart = $this->factory->part(Integration::class, (array) $data, true);

        /** @var ?Guild */
        if ($guild = yield $this->discord->guilds->cacheGet($data->guild_id)) {
            $guild->integrations->set($data->id, $integrationPart);
        }

        if (isset($data->user)) {
            $this->cacheUser($data->user);
        }

        return $integrationPart;
    }
}
