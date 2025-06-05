package com.jeric.ricafrente.naruto.core.network.model.akatsuki

import kotlinx.serialization.Serializable

@Serializable
data class AkatsukiResponse(
    val akatsuki: List<AkatsukiDto>,
    val currentPage: Int,
    val pageSize: Int,
    val total: Int
)