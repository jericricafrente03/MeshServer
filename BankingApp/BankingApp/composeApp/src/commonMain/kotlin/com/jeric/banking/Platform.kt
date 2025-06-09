package com.jeric.banking

interface Platform {
    val name: String
}

expect fun getPlatform(): Platform