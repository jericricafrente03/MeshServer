package com.jeric.ricafrente.naruto.core.network.model.buruto

import kotlinx.serialization.Serializable


@Serializable
data class BorutoResponse(
    val currentPage: Int,
    val kara: List<KaraDto>,
    val pageSize: Int,
    val total: Int
)