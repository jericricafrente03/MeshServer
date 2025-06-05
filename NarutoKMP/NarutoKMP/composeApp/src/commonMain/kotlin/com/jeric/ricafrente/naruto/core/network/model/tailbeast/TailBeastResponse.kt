package com.jeric.ricafrente.naruto.core.network.model.tailbeast


import kotlinx.serialization.SerialName
import kotlinx.serialization.Serializable

@Serializable
data class TailBeastResponse(
    val currentPage: Int,
    val pageSize: Int,
    @SerialName("tailed-beasts") val tailedBeasts: List<TailedBeastDto>,
    val total: Int
)