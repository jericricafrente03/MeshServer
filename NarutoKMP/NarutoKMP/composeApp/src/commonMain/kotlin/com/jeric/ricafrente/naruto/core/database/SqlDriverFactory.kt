package com.jeric.ricafrente.naruto.core.database

import app.cash.sqldelight.db.SqlDriver
import org.koin.core.scope.Scope

expect fun Scope.sqlDriverFactory(): SqlDriver
fun createDatabase(driver: SqlDriver): NarutoKMP {
    val database = NarutoKMP(
        driver = driver,
    )
    return database
}