package com.jeric.ricafrente.naruto.core.network.model.clan

import kotlinx.serialization.Serializable

@Serializable
data class ClanResponse(
    val clans: List<ClanDto>,
    val currentPage: Int,
    val pageSize: Int,
    val total: Int
)