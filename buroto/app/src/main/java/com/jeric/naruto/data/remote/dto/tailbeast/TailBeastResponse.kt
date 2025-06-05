package com.jeric.naruto.data.remote.dto.tailbeast

data class TailBeastResponse(
    val currentPage: Int,
    val pageSize: Int,
    val tailedBeasts: List<TailedBeastDto>,
    val total: Int
)