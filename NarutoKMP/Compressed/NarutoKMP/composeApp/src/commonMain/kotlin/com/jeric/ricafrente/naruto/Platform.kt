package com.jeric.ricafrente.naruto

interface Platform {
    val name: String
}

expect fun getPlatform(): Platform