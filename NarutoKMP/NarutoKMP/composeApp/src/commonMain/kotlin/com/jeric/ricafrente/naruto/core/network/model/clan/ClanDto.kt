package com.jeric.ricafrente.naruto.core.network.model.clan

import kotlinx.serialization.Serializable

@Serializable
data class ClanDto(
    val characters: List<Int>,
    val id: Int,
    val name: String
)