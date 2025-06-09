package com.jeric.ricafrente.naruto.core.network.client

import com.jeric.ricafrente.naruto.core.network.helper.NarutoApi
import com.jeric.ricafrente.naruto.core.network.helper.handleErrors
import com.jeric.ricafrente.naruto.core.network.model.akatsuki.AkatsukiResponse
import com.jeric.ricafrente.naruto.core.network.model.buruto.BorutoResponse
import com.jeric.ricafrente.naruto.core.network.model.character.CharacterDto
import com.jeric.ricafrente.naruto.core.network.model.character.CharactersResponse
import com.jeric.ricafrente.naruto.core.network.model.clan.ClanResponse
import com.jeric.ricafrente.naruto.core.network.model.tailbeast.TailBeastResponse
import com.jeric.ricafrente.naruto.core.utils.Constants
import io.ktor.client.HttpClient
import io.ktor.client.call.body
import io.ktor.client.request.get
import io.ktor.client.request.parameter

class NarutoClient(private val httpClient: HttpClient) : NarutoApi {

    private suspend inline fun <reified T> get(endpoint: String, params: Map<String, Any?> = emptyMap()): T =
        handleErrors {
            httpClient.get("${Constants.BASE_URL}/$endpoint") {
                params.forEach { (key, value) -> parameter(key, value) }
            }.body()
        }

    override suspend fun getAllCharacters(page: Int, limit: Int): CharactersResponse =
        get("characters", mapOf("page" to page, "limit" to limit))

    override suspend fun searchCharactersByName(name: String, page: Int, limit: Int): CharactersResponse =
        get("characters", mapOf("name" to name, "page" to page, "limit" to limit))

    override suspend fun getCharacterById(id: String): CharacterDto =
        get("characters/$id")

    override suspend fun getAllTailBeasts(): TailBeastResponse =
        get("tailed-beasts")

    override suspend fun getClan(): ClanResponse =
        get("clans")

    override suspend fun getAkatsuki(): AkatsukiResponse =
        get("akatsuki")

    override suspend fun getBoruto(): BorutoResponse =
        get("kara")
}