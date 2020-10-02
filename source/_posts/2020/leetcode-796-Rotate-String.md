---
title: LeetCode 796. Rotate String
date: 2020-05-11 00:40:02
tags:
- leetcode
- javascript
- algorithm
img: /medias/page_images/2020/leetcode-796-rotate-string.png
categories: leetcode algorithms
---
* link：[796. Rotate String](https://leetcode.com/problems/rotate-string/)
* difficulty：`easy`
* language：`javascript`

## 題目原文

We are given two strings, `A` and `B`.

A shift on `A` consists of taking string A and moving the leftmost character to the rightmost position. For example, if `A = 'abcde`', then it will be `'bcdea'` after one shift on `A`. Return `True` if and only if `A` can become `B` after some number of shifts on `A`.


```
Example 1:
Input: A = 'abcde', B = 'cdeab'
Output: true

Example 2:
Input: A = 'abcde', B = 'abced'
Output: false
```

Note:

* `A` and `B` will have length at most 100.

### 題目解釋

給予兩個字串，判斷這兩個字串是否為同樣字串，只是起始的位置不同的字串而已，好比 `abc` 等同於 `bca` 或 `cab`。

## 解題思路

1. **檢查長度**：首先可以先驗證兩個參數的長度，不一樣就直接返回 `false`。
2. **比較運算**：這部分目前有兩種方法 **遍歷** 以及 **Repeat**


### 比較運算：遍歷

首先取 `A` 的第一個 字元，然後去搜尋其 在 `B` 的位置，然後遍歷比較，由於 `A` 和 `B` 遍歷的時候， index 不一樣，所以必須另外寫一個算式去將 `A` 的 index 轉換成 `B` 的 index，時間複雜度為 `O(n²)`。

### 比較運算：Repeat

將 `A` 重複組成一遍，再使用 像是 `includes` 或是 `indexOf` 等 method 搜尋，時間複雜度為 `1`，明顯最快。

## 實作

我們選擇用 `Repeat` 的方式去做 **比較運算**

```javascript
var rotateString = function (A, B) {
    return A.length === B.length && A.repeat(2).includes(B);
};
```
